#!/bin/bash


# Establish a SSH tunnel with port forwarding to a remote database server.
#
# @param {string} ssh_host The hostname or IP address of the SSH server.
# @param {string} ssh_user The username for the SSH connection.
# @param {string} ssh_key The file path to the SSH private key for authentication.
# @param {string} local_port The local port to forward to the remote MySQL server's port 3306.
# @param {string} remote_db_host The hostname or IP address of the remote MySQL server, accessible from the SSH host.
#
# @returns {string} The Process ID (PID) of the background SSH tunnel process.
#
# Usage: TUNNEL_PID=sst_establish_ssh_tunnel_to_mysql <ssh_host> <ssh_user> <ssh_key> <local_port> <remote_db_host>
# Example: TUNNEL_PID=sst_establish_ssh_tunnel_to_mysql "ssh.example.com" "user" "/path/to/key" "3307" "db.example.com"
sst_establish_ssh_tunnel_to_mysql() {
    
    # TODO --- NOT TESTED!!
    
    local ssh_host="$1"
    local ssh_user="$2"
    local ssh_key="$3"
    local local_port="$4"
    local remote_db_host="$5"

    echo "Establishing SSH Tunnel..."
    ssh -i "$ssh_key" -N -L "$local_port":"$remote_db_host":3306 -o StrictHostKeyChecking=no "$ssh_user@$ssh_host" &
    
    # Capture the Process ID of the tunnel
    local tunnel_pid=$!
    
    # Ensure the tunnel is closed when the script exits
    trap "kill $tunnel_pid" EXIT

    echo "Tunnel established with PID $tunnel_pid mapping 127.0.0.1:$local_port -> $remote_db_host:3306"
    
    # Give the tunnel a moment to connect
    sleep 3 
    
    echo "$tunnel_pid"
}