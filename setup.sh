#!/bin/bash

# Make script executable
chmod +x setup.sh

# Build and start the Docker containers
echo "Building and starting Docker containers..."
docker compose up -d --build

# Install PHP dependencies
echo "Installing PHP dependencies..."
docker compose exec app composer install

# Install Node.js dependencies
echo "Installing Node.js dependencies..."
docker compose exec app npm install

# Generate application key
echo "Generating application key..."
docker compose exec app php artisan key:generate --ansi

# linking storage
echo "Linking storage disk..."
docker compose exec app php artisan storage:link --ansi

# Run database migrations
echo "Running database migrations..."
docker compose exec app php artisan migrate --seed

# Build frontend assets
echo "Building frontend assets..."
docker compose exec app npm run build

echo "Setup complete! The application is now running at http://localhost:8000"