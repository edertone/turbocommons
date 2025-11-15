#!/bin/bash


# Installs Prometheus, Grafana, and Node Exporter using Docker to monitor system metrics.
# It creates the necessary configuration files and directories under /opt/prometheus-grafana
# and uses docker-compose to manage the services.
# Prometheus will be available at http://<host-ip>:9090
# Grafana will be available at http://<host-ip>:3000 (default user/pass: admin/admin)
smt_install_prometheus_grafana() {
    echo -e "\nSetting up Prometheus, Grafana, and Node Exporter..."

    # Ensure Docker is installed
    if ! command -v docker &> /dev/null; then
        echo "ERROR: Docker is not installed. Please install Docker and try again."
        exit 1
    fi

    # Define paths were the monitoring containers docker compose project will reside
    local base_dir="/opt/prometheus-grafana"
    local prometheus_dir="$base_dir/prometheus"
    local grafana_data_dir="$base_dir/grafana-data"
    local compose_file="$base_dir/docker-compose.yml"
    local prometheus_config="$prometheus_dir/prometheus.yml"

    # Check if setup is already complete
    if [ -f "$compose_file" ] && docker compose -f "$compose_file" ps | grep -q "Up"; then
        echo "Prometheus and Grafana are already running."
        return 0
    fi

    # Create directories
    mkdir -p "$prometheus_dir"
    mkdir -p "$grafana_data_dir"
    chmod 777 "$grafana_data_dir" # Grafana container runs as non-root and needs write access

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

    # Create docker-compose.yml
    cat > "$compose_file" << EOF
services:
  prometheus:
    image: prom/prometheus:latest
    container_name: prometheus
    volumes:
      - ./prometheus:/etc/prometheus
    command:
      - '--config.file=/etc/prometheus/prometheus.yml'
    ports:
      - "127.0.0.1:9090:9090"
    restart: unless-stopped

  grafana:
    image: grafana/grafana:latest
    container_name: grafana
    volumes:
      - ./grafana-data:/var/lib/grafana
    ports:
      - "3000:3000"
    restart: unless-stopped

  node-exporter:
    image: prom/node-exporter:latest
    container_name: node-exporter
    ports:
      - "9100:9100"
    restart: unless-stopped
EOF

    # Start services using Docker Compose
    if ! docker compose -f "$compose_file" up -d --quiet-pull > /dev/null; then
        echo "ERROR: Failed to start monitoring stack with Docker Compose."
        docker compose -f "$compose_file" logs
        return 1
    fi

    echo "Prometheus and Grafana setup complete."
    echo "Grafana is accessible at http://<your-ip>:3000 (default login: admin/admin)"
    docker compose -f "$compose_file" ps
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
# Parameters: <admin_user> <admin_pass>
# Usage: smt_setup_prometheus_as_grafana_datasource "admin" "admin"
smt_setup_prometheus_as_grafana_datasource() {
    local admin_user="$1"
    local admin_pass="$2"
    
    # Validate input parameters
    if [ -z "$admin_user" ] || [ -z "$admin_pass" ]; then
        echo "Usage: smt_setup_prometheus_as_grafana_datasource <admin_user> <admin_pass>"
        return 1
    fi
    
    echo -e "\nSetting up Prometheus as Grafana datasource..."

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
        return 0
    else
        echo "ERROR: Failed to add Prometheus datasource."
        echo "Response: $response"
        return 1
    fi
    
    echo ""
}



# Configures Grafana with Prometheus as a data source and imports dashboard 1860.
# This function should be called after smt_install_prometheus_grafana.
#smt_setup_grafana_dashboard_1860() {
#    echo -e "\nConfiguring Grafana data source and dashboard..."
#
#    local admin_user="$1"
#    local admin_pass="$2"
#    local grafana_url="http://$admin_user:$admin_pass@localhost:3000"
#    local datasource_name="Prometheus"
#    local dashboard_id=1860
#
#    # Wait for Grafana to be ready
#    echo "Waiting for Grafana to be available..."
#    until $(curl --output /dev/null --silent --head --fail "$grafana_url"); do
#        printf '.'
#        sleep 5
#    done
#    echo "Grafana is up!"
#
#    # 1. Check if data source already exists
#    if curl -s "$grafana_url/api/datasources/name/$datasource_name" | grep -q "name"; then
#        echo "Data source '$datasource_name' already exists."
#    else
#        echo "Creating Prometheus data source..."
#        curl -s -X POST "$grafana_url/api/datasources" \
#        -H "Content-Type: application/json" \
#        --data-binary @- << EOF
#{
#    "name": "$datasource_name",
#    "type": "prometheus",
#    "url": "http://prometheus:9090",
#    "access": "proxy",
#    "isDefault": true
#}
#EOF
#        echo -e "\nPrometheus data source created."
#    fi
#
#    # 2. Import dashboard 1860 (Node Exporter Full)
#    echo "Importing dashboard ID $dashboard_id..."
#    
#    # Get the dashboard JSON model from grafana.com via the Grafana API
#    local dashboard_json=$(curl -s "$grafana_url/api/gnet/dashboards/$dashboard_id" | jq .json)
#
#    # Prepare the payload for importing the dashboard
#    local import_payload=$(echo "$dashboard_json" | jq \
#        --arg ds_name "$datasource_name" \
#        '{
#            "dashboard": .,
#            "overwrite": true,
#            "inputs": [
#                {
#                    "name": "DS_PROMETHEUS",
#                    "type": "datasource",
#                    "pluginId": "prometheus",
#                    "value": $ds_name
#                }
#            ]
#        }')
#
#    # Import the dashboard
#    curl -s -X POST "$grafana_url/api/dashboards/db" \
#    -H "Content-Type: application/json" \
#    --data-binary "$import_payload"
#
#    echo -e "\nDashboard $dashboard_id imported successfully."
#    echo -e "Configuration complete. You can now access the dashboard in Grafana.\n"
#}
