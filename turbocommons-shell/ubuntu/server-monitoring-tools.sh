#!/bin/bash


# Installs Prometheus, Grafana, and Node Exporter using Docker to monitor system metrics.
# It creates the necessary configuration files and directories under /opt/prometheus-grafana
# and uses docker-compose to manage the services.
# Grafana will be available at http://<host-ip>:3000 with the provided user and password
# (defaults to admin/admin if not specified).
# Usage: smt_install_prometheus_grafana <grafana_admin_user> <grafana_admin_password> <prometheus_retention_size>
# Example: smt_install_prometheus_grafana "admin" "strongpassword" "2GB"
smt_install_prometheus_grafana() {
    local admin_user="${1:-admin}"
    local admin_password="${2:-admin}"
    local retention_size="${3:-1GB}"

    echo -e "\nSetting up Prometheus, Grafana, and Node Exporter..."
    
    # Ensure Docker is installed
    if ! command -v docker &> /dev/null; then
        echo "ERROR: Docker is not installed. Please install Docker and try again."
        exit 1
    fi
    
    # Define paths where the monitoring containers docker compose project will reside
    local base_dir="/opt/prometheus-grafana"
    local compose_file="$base_dir/docker-compose.yml"
    local prometheus_dir="$base_dir/docker/prometheus"
    local prometheus_config="$prometheus_dir/prometheus.yml"
    local prometheus_data_dir="$base_dir/data/prometheus"
    local grafana_data_dir="$base_dir/data/grafana"
    
    # Check if setup is already complete
    if [ -f "$compose_file" ] && docker compose -f "$compose_file" ps | grep -q "Up"; then
        echo "Prometheus and Grafana are already running."
        return 0
    fi
    
    # Create directories
    mkdir -p "$prometheus_dir"
    mkdir -p "$grafana_data_dir"
    chmod 777 "$grafana_data_dir" # Grafana container runs as non-root and needs write access
    mkdir -p "$prometheus_data_dir"
    chmod 777 "$prometheus_data_dir"  # Prometheus runs as non-root (uid 65534), so ensure write access

    # Create prometheus.yml
    cat > "$prometheus_config" << EOF
global:
  scrape_interval: 15s
    
scrape_configs:
  - job_name: 'prometheus'
    static_configs:
      - targets: ['localhost:9090']
  - job_name: 'node-exporter'
    static_configs:
      - targets: ['node-exporter:9100']
EOF
    
    # Create docker-compose.yml with environment variables for Grafana admin credentials
    cat > "$compose_file" << EOF
services:
  prometheus:
    image: prom/prometheus:latest
    container_name: prometheus
    volumes:
      - ./docker/prometheus:/etc/prometheus
      - ./data/prometheus:/prometheus
    command:
      - '--config.file=/etc/prometheus/prometheus.yml'
      - '--storage.tsdb.path=/prometheus'
      - 'storage.tsdb.retention.size=$retention_size'
    ports:
      - "127.0.0.1:9090:9090"
    restart: unless-stopped
    
  grafana:
    image: grafana/grafana:latest
    container_name: grafana
    volumes:
      - ./data/grafana:/var/lib/grafana
    environment:
      - GF_SECURITY_ADMIN_USER=$admin_user
      - GF_SECURITY_ADMIN_PASSWORD=$admin_password
    ports:
      - "3000:3000"
    restart: unless-stopped
    
  node-exporter:
    image: prom/node-exporter:latest
    container_name: node-exporter
    ports:
      - "127.0.0.1:9100:9100"
    restart: unless-stopped
EOF
    
    # Start services using Docker Compose
    if ! docker compose -f "$compose_file" up -d --quiet-pull > /dev/null; then
        echo "ERROR: Failed to start monitoring stack with Docker Compose."
        docker compose -f "$compose_file" logs
        return 1
    fi
    
    echo "Prometheus and Grafana setup complete."
    docker compose -f "$compose_file" ps
    echo ""
    echo "Grafana is accessible at http://<your-ip>:3000 (login: $admin_user/$admin_password)"
    echo ""
}


# Waits for Grafana to be ready by polling its HTTP endpoint.
# Returns 0 if Grafana is ready, 1 if it times out.
smt_wait_for_grafana_to_be_ready() {
    echo -e "Waiting for Grafana to be available..."

    local grafana_url="http://localhost:3000"
    local max_attempts=20
    local attempt=0
    while [ $attempt -lt $max_attempts ]; do
        if curl -s -f "$grafana_url" > /dev/null; then
            break
        fi
        sleep 5
        attempt=$((attempt+1))
    done

    if [ $attempt -eq $max_attempts ]; then
        echo "ERROR: Grafana did not start. Please check the container status."
        return 1
    fi
}


# Sets up Prometheus as a datasource in Grafana using the Grafana API.
# Assumes Grafana is running on localhost:3000
# It will attempt to add the datasource if it doesn't exist.
# Parameters: <grafana_admin_user> <grafana_admin_pass>
# Usage: smt_setup_prometheus_as_grafana_datasource "admin" "admin"
smt_setup_prometheus_as_grafana_datasource() {
    local admin_user="$1"
    local admin_pass="$2"
    
    # Validate input parameters
    if [ -z "$admin_user" ] || [ -z "$admin_pass" ]; then
        echo "Usage: smt_setup_prometheus_as_grafana_datasource <admin_user> <admin_pass>"
        return 1
    fi
    
    echo "Setting up Prometheus as Grafana datasource..."

    local grafana_url="http://localhost:3000"
    local api_endpoint="/api/datasources"
    local datasource_name="Prometheus"
    
    # Ensure curl is installed
    if ! command -v curl &> /dev/null; then
        echo "ERROR: curl is not installed. Please install curl and try again."
        return 1
    fi

    # Wait for Grafana to be ready
    if ! smt_wait_for_grafana_to_be_ready; then
        return 1
    fi

    # Check if datasource already exists
    local existing_ds=$(curl -s -u "$admin_user:$admin_pass" "$grafana_url$api_endpoint" | grep -o "\"name\":\"$datasource_name\"")

    if [ -n "$existing_ds" ]; then
        echo "Prometheus datasource already exists in Grafana."
        return 0
    fi

    # Add Prometheus datasource
    local payload='{
        "name": "Prometheus",
        "type": "prometheus",
        "url": "http://prometheus:9090",
        "access": "proxy",
        "basicAuth": false,
        "isDefault": true
    }'

    local response=$(curl -s -X POST -H "Content-Type: application/json" -u "$admin_user:$admin_pass" "$grafana_url$api_endpoint" -d "$payload")

    if echo "$response" | grep -q "Datasource added"; then
        echo "Prometheus successfully set as Grafana datasource."
        echo ""
        return 0
    else
        echo "ERROR: Failed to add Prometheus datasource."
        echo "Response: $response"
        echo ""
        return 1
    fi
}


# Imports the specified dashboard into Grafana using the Grafana API.
# A famous dashboard ID is 1860 (Node Exporter Full)
# Usage: smt_import_dashboard_into_grafana <grafana_admin_user> <grafana_admin_pass> <dashboard_id>
# Example: smt_import_dashboard_into_grafana "admin" "admin" "1860"
smt_import_dashboard_into_grafana() {
    local admin_user="$1"
    local admin_pass="$2"
    local dashboard_id="$3"
    
    # Validate input parameters
    if [ -z "$admin_user" ] || [ -z "$admin_pass" ] || [ -z "$dashboard_id" ]; then
        echo "Usage: smt_import_dashboard_into_grafana <grafana_admin_user> <grafana_admin_pass> <dashboard_id>"
        return 1
    fi
    
    echo "Importing dashboard $dashboard_id into Grafana..."
    local grafana_url="http://localhost:3000"
    local api_endpoint="/api/dashboards/db"
    
    # Ensure curl is installed
    if ! command -v curl &> /dev/null; then
        echo "ERROR: curl is not installed. Please install curl and try again."
        return 1
    fi
    
    # Wait for Grafana to be ready
    if ! smt_wait_for_grafana_to_be_ready; then
        return 1
    fi
    
    # Download the dashboard JSON from grafana.com
    local dashboard_download_url="https://grafana.com/api/dashboards/$dashboard_id/revisions/latest/download"
    local downloaded_json=$(curl -s "$dashboard_download_url")
    if [ -z "$downloaded_json" ] || echo "$downloaded_json" | grep -q "not found"; then
        echo "ERROR: Failed to download dashboard JSON from $dashboard_download_url."
        return 1
    fi
    
    # Import the dashboard using the correct payload
    local response=$(curl -s -X POST -H "Content-Type: application/json" -u "$admin_user:$admin_pass" "$grafana_url$api_endpoint" -d @- <<EOF
{
  "dashboard": $downloaded_json,
  "overwrite": true,
  "folderUid": ""
}
EOF
)
    if echo "$response" | grep -q "success"; then
        echo "Dashboard $dashboard_id successfully imported into Grafana."
        echo ""
        return 0
    else
        echo "ERROR: Failed to import dashboard $dashboard_id."
        echo "Response: $response"
        echo ""
        return 1
    fi
}
