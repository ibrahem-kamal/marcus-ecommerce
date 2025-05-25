#!/bin/bash

# Make script executable
chmod +x stop.sh

# Stop and remove Docker containers
echo "Stopping Docker containers..."
docker compose down

echo "Docker containers have been stopped and removed."