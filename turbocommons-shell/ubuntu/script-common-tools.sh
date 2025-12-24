#!/bin/bash


# Print text in green color, usually to indicate success
sct_echo_green() {
    echo -e "\e[32m$1\e[0m"
}

# Print text in yellow color, usually to indicate warnings or important info
sct_echo_yellow() {
    echo -e "\e[33m$1\e[0m"
}

# Print text in red color, usually to indicate errors
sct_echo_red() {
    echo -e "\e[31m$1\e[0m"
}

# Enables immediate exit on error and sets a custom error trap
# so that any error will print a message before exiting
sct_enable_global_errors_handling() {
    set -e
    trap 'sct_echo_red "An error occurred. Exiting..." ; exit 1' ERR
}

# Check if the current script runs as root
sct_script_must_run_as_root() {
    if [ "$EUID" -ne 0 ]; then
        sct_echo_red "Please run this script as root"
        exit 1
    fi
}

# Verify the specified user exists or fail with a custom error message
# Usage: sct_user_must_exist "username" "Custom error message"
sct_user_must_exist() {
    local username="$1"
    local message="$2"
    if ! id -u "$username" > /dev/null 2>&1; then
        sct_echo_red "ERROR: User '$username' does not exist. $message"
        exit 1
    fi
}

# Reads global variables from an environment file and exports them as global variables
# if the file does not exist, the provided message will be printed and the function returns
# Lines starting with # are ignored as comments
# Usage: sct_read_global_variables_from_env_file "/path/to/envfile" "File not found message"
sct_read_global_variables_from_env_file() {
    local env_file="$1"
    local file_not_found_message="$2"
    if [ ! -f "$env_file" ]; then
        echo "$file_not_found_message"
        return 0
    fi

    # Use sed to remove carriage returns, then process lines
    # that are not comments and not empty.
    # The `|| [[ -n "$line" ]]` part ensures the last line is read even if it's missing a newline.
    while IFS= read -r line || [[ -n "$line" ]]; do
        if [[ -n "$line" && ! "$line" =~ ^# ]]; then
            export "$line"
        fi
    done < <(sed 's/\r$//' "$env_file")
}

# Checks if curl is installed, exits with error if not.
sct_curl_must_be_installed() {
    if ! command -v curl &> /dev/null; then
        sct_echo_red "ERROR: curl is not installed. Please install curl and try again."
        exit 1
    fi
}

# Checks if docker is installed, exits with error if not.
sct_docker_must_be_installed() {
    if ! command -v docker &> /dev/null; then
        sct_echo_red "ERROR: Docker is not installed. Please install Docker and try again."
        exit 1
    fi
}

# Install Docker CE if not already installed
sct_install_docker_if_not_exists() {
    if ! command -v docker &> /dev/null; then
        echo "Installing Docker CE..."
        apt-get update > /dev/null
        apt-get install -y ca-certificates curl gnupg lsb-release > /dev/null
        install -m 0755 -d /etc/apt/keyrings
        curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
        echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null
        apt-get update > /dev/null
        apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin > /dev/null
        echo "Docker CE installation complete. Version: $(docker --version)"
    fi
}

# Setup swap file if not present, with configurable size (e.g., 2G, 2048M)
# Usage: sct_setup_swap 2G or sct_setup_swap 2048M
sct_setup_swap_if_not_enabled() {
    local swap_size="$1"
    if [ -z "$swap_size" ]; then
        sct_echo_yellow "Usage: sct_setup_swap <size> (e.g., 2G or 2048M)"
        return 1
    fi
    if ! swapon --show | grep -q '^'; then
        echo "No swap found. Creating swap file of size $swap_size..."
        fallocate -l "$swap_size" /swapfile || dd if=/dev/zero of=/swapfile bs=1M count=$(echo "$swap_size" | grep -oE '[0-9]+')
        chmod 600 /swapfile
        mkswap /swapfile > /dev/null
        swapon /swapfile
        echo '/swapfile none swap sw 0 0' >> /etc/fstab
        echo "Swap created and enabled."
    fi
}

# Install zip cmd if not already installed
sct_install_zip_if_not_exists() {
    if ! command -v zip &> /dev/null; then
        echo "zip not found, installing..."
        sudo apt-get update > /dev/null
        sudo apt-get install -y zip > /dev/null
        echo "zip installation complete."
    fi
}

# Create and setup a SFTP user
# important: The SFTP root folder must exist and be owned by root
# Usage: sct_create_and_setup_sftp_user "username" "password" "group" "sftp_root_folder"
sct_create_and_setup_sftp_user() {
    local USERNAME="$1"
    local USER_PASSWORD="$2"
    local USER_GROUP="$3"
    local SFTP_ROOT_FOLDER="$4"

    # Validate input parameters
    if [ -z "$USERNAME" ] || [ -z "$USER_PASSWORD" ] || [ -z "$USER_GROUP" ] || [ -z "$SFTP_ROOT_FOLDER" ]; then
        sct_echo_red "Usage: sct_create_and_setup_sftp_user <username> <password> <group> <sftp_root_folder>"
        return 1
    fi
    
    # Ensure SFTP_ROOT_FOLDER folder exists and is owned by root
    if [ ! -d "$SFTP_ROOT_FOLDER" ] || [ "$(stat -c '%U' "$SFTP_ROOT_FOLDER")" != "root" ]; then
        sct_echo_red "ERROR: SFTP root folder '$SFTP_ROOT_FOLDER' must exist and be owned by root."
        return 1
    fi
    
    echo "Setting up SFTP user '$USERNAME:$USER_GROUP' with SFTP root folder '$SFTP_ROOT_FOLDER'..."
    
    # Create group if it does not exist
    if ! getent group "$USER_GROUP" > /dev/null; then
        groupadd "$USER_GROUP" > /dev/null
    fi
    
    # Create user if it does not exist
    if ! id -u "$USERNAME" >/dev/null 2>&1; then
        adduser --system --ingroup "$USER_GROUP" --shell=/usr/sbin/nologin "$USERNAME" > /dev/null
    else
        sct_echo_red "ERROR: User '$USERNAME' already exists."
        return 1
    fi

    # Set user password
    echo "$USERNAME:$USER_PASSWORD" | chpasswd > /dev/null
    
    # Configure SFTP access in sshd_config file
    local sftp_config="    
Match User $USERNAME
    ForceCommand internal-sftp
    ChrootDirectory $SFTP_ROOT_FOLDER
    PasswordAuthentication yes
    PermitTTY no
    X11Forwarding no
    AllowTcpForwarding no
    AllowAgentForwarding no"

    if ! grep -q "Match User $USERNAME" /etc/ssh/sshd_config; then
        echo "$sftp_config" >> /etc/ssh/sshd_config
        systemctl restart ssh
    fi
    
    echo "User SFTP '$USERNAME' created successfully."
}

# Prompt for a value and export it to the given variable name (empty input is not allowed)
# NOTICE: If the variable is already set, nothing is done
# Usage: sct_prompt_for_variable VAR_NAME "message"
sct_prompt_for_variable() {
    local var_name="$1"
    local prompt_message="$2"
    local current_value="${!var_name}"

    if [ -n "$current_value" ]; then
        sct_echo_yellow "$var_name is set with value: $current_value"
        return 0
    fi

    local user_input
    read -p "$prompt_message:" user_input
    if [ -z "$user_input" ]; then
        sct_echo_red "ERROR: Input cannot be empty."
        return 1
    fi
    export "$var_name=$user_input"
}

# Prompt the user for a value and export it to the given variable name, or use default if input is empty
# NOTICE: If the variable is already set, nothing is done
# Usage: sct_prompt_for_variable_or_default VAR_NAME "message" "default-value"
sct_prompt_for_variable_or_default() {
    local var_name="$1"
    local prompt_message="$2"
    local default_value="$3"
    local current_value="${!var_name}"

    if [ -n "$current_value" ]; then
        sct_echo_yellow "$var_name is set with value: $current_value"
        return 0
    fi

    local user_input
    read -p "$prompt_message [$default_value]:" user_input
    user_input=${user_input:-$default_value}
    export "$var_name=$user_input"
}

# Create a folder if it does not exist and set its permissions and ownership
# Usage: sct_create_dir_if_missing_and_set_permisions "/path/to/folder" "permissions" "user:group"
sct_create_dir_if_missing_and_set_permisions() {
    local dir="$1"
    local perm="$2"
    local userandgroup="$3"

    if [ ! -d "$dir" ]; then
        mkdir -p "$dir"
        fi

    chown -R "$userandgroup" "$dir" || { sct_echo_red "ERROR: Failed to set ownership for $dir"; return 1; }
    chmod -R "$perm" "$dir" || { sct_echo_red "ERROR: Failed to set permissions for $dir"; return 1; }

    echo "Folder '$dir' assigned to '$userandgroup' with permissions '$perm'."
}


# Create a file if it does not exist and set its permissions and ownership
# Usage: sct_create_file_if_missing_and_set_permisions "/path/to/file" "permissions" "user:group"
sct_create_file_if_missing_and_set_permisions() {
    local file="$1"
    local perm="$2"
    local userandgroup="$3"

    if [ ! -f "$file" ]; then
        mkdir -p "$(dirname "$file")"
        touch "$file"
    fi

    chown "$userandgroup" "$file" || { sct_echo_red "ERROR: Failed to set ownership for $file"; return 1; }
    chmod "$perm" "$file" || { sct_echo_red "ERROR: Failed to set permissions for $file"; return 1; }

    echo "File '$file' assigned to '$userandgroup' with permissions '$perm'."
}


# Start Docker containers using docker compose UP
# Work dir must be the one containing docker-compose.yml
# Additional environment variables can be passed as arguments
# Usage: sct_docker_compose_up_with_env_vars VAR1=value1 VAR2=value2 ...
sct_docker_compose_up_with_env_vars() {
    
    # Export the provided environment variables
    echo -e "\nStarting ALL Docker containers with custom env vars..."
    for env_var in "$@"; do
        var_name="${env_var%%=*}"
        var_value="${env_var#*=}"
        export "$var_name"="$var_value"
    done

    # Start Docker containers
    if ! docker compose up -d --quiet-pull &> /dev/null; then
        sct_echo_red "Error: Failed to start Docker containers."
        docker compose logs
        return 1
    fi

    echo -e "\nDocker containers launched. Status:"
    docker compose ps
    echo -e "\n"
}


# Start a single Docker container using docker compose UP
# Work dir must be the one containing docker-compose.yml
# ONLY the specified container will be started, all others will remain in their current state
# Additional environment variables can be passed as arguments
# Usage: sct_docker_compose_up_single_container_with_env_vars <container_name> VAR1=value1 VAR2=value2 ...
sct_docker_compose_up_single_container_with_env_vars() {
    
    local container_name="$1"
    shift

    if [ -z "$container_name" ]; then
        sct_echo_red "Usage: sct_docker_compose_up_single_container_with_env_vars <container_name> [VAR1=value1 ...]"
        return 1
    fi

    echo -e "\nStarting Docker container '$container_name' with custom env vars..."
    for env_var in "$@"; do
        var_name="${env_var%%=*}"
        var_value="${env_var#*=}"
        export "$var_name"="$var_value"
    done

    if ! docker compose up -d --quiet-pull "$container_name" &> /dev/null; then
        sct_echo_red "Error: Failed to start Docker container '$container_name'."
        docker compose logs "$container_name"
        return 1
    fi

    echo -e "\nDocker container '$container_name' launched. Status:"
    docker compose ps "$container_name"
    echo -e "\n"
}


# Wait for a Docker container to reach healthy status
# Parameters are the container name, max retries (default 30), and sleep time between checks in seconds (default 4)
# Usage: sct_docker_wait_for_healthy_status "container_name" [max_retries] [sleep_time_seconds]
sct_docker_wait_for_healthy_status() {
    
    local container_name="$1"
    local max_retries="${2:-30}"  # Default to 30 attempts
    local sleep_time="${3:-4}"    # Default to 4 seconds sleep
    local count=0

    echo -e "\nWaiting for container '$container_name' to be healthy..."

    while [ $count -lt $max_retries ]; do
    
        # inspect returns the health status (starting, healthy, unhealthy, or creates error if no healthcheck)
        status=$(docker inspect --format '{{if .State.Health}}{{.State.Health.Status}}{{else}}none{{end}}' "$container_name" 2>/dev/null)

        # Check the status
        if [ "$status" = "healthy" ]; then
            echo -e "Success: '$container_name' is healthy!\n"
            return 0
        elif [ "$status" = "unhealthy" ]; then
            echo -e "Error: '$container_name' reports status 'unhealthy'. Aborting.\n"
            return 1
        elif [ "$status" = "none" ]; then
            echo -e "Error: '$container_name' has no HEALTHCHECK defined.\n"
            return 1
        elif [ -z "$status" ]; then
            echo -e "Error: Container '$container_name' not found or not running.\n"
            return 1
        fi

        count=$((count + 1))
        sleep "$sleep_time"
    done

    echo -e "Error: Timeout waiting for '$container_name' to become healthy.\n"
    return 1
}


# Stop ALL Docker containers using docker compose DOWN
# Work dir must be the one containing docker-compose.yml
sct_docker_compose_down() {
    
    echo -e "\nStopping ALL Docker containers..."
    
    # Stop Docker containers
    if ! docker compose down &> /dev/null; then
        sct_echo_red "Error: Failed to stop Docker containers."
        docker compose logs
        return 1
    fi

    echo -e "ALL Docker containers stopped.\n"
}

# Add a cron job for the current user, avoiding duplicates (if the job exists, nothing is done)
# Job will be added to the user that is currently running the script
# IMPORTANT: The schedule will use the system timezone, normally UTC
# Usage: sct_add_cron_job "0 5 * * *" "task command"
# Example: sct_add_cron_job "0 5 * * *" "echo hello"
sct_add_cron_job() {
    local schedule="$1"
    local command="$2"
    
    local job="$schedule $command"
    local current_crontab=$(crontab -l 2>/dev/null)

    # Check if the job already exists
    if echo "$current_crontab" | grep -Fxq -- "$job"; then
        echo "Cron job already exists, nothing done: $job"
        return 0
    fi

    # Add the new cron job, handling empty initial crontab
    if [ -z "$current_crontab" ]; then
        echo "$job" | crontab -
    else
        (echo "$current_crontab"; echo "$job") | crontab -
    fi

    # Test if the job was added
    if crontab -l 2>/dev/null | grep -Fxq -- "$job"; then
        echo "Cron job added successfully: $job"
    else
        sct_echo_red "ERROR: Failed to add cron job: $job"
        return 1
    fi
}

# Executes a script in a subshell with the provided environment variables.
# This prevents the subscript from exiting the parent script if something goes wrong.
# Usage: execute_subscript_with_env "/path/to/script.sh" "VAR1=value1" "VAR2=value2" ...
sct_execute_subscript_isolated_with_env_vars() {
    local script_path="$1"
    shift
    
    if [ ! -f "$script_path" ]; then
        sct_echo_red "Subscript not found at '$script_path'"
        return 1
    fi

    chmod +x "$script_path"

    # Execute the script in a subshell `( ... )` with the specified environment variables.
    # This isolates the execution and prevents `exit` calls from affecting the parent script.
    (env "$@" "$script_path")
}

