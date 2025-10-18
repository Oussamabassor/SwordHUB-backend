#!/bin/bash

# Wait for MongoDB to be ready (optional, but good practice)
echo "Waiting for MongoDB connection..."
sleep 2

# Start Apache in foreground
echo "Starting Apache..."
apache2-foreground
