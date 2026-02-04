#!/bin/bash
# Manual storage link creation script
# Run this on your server via SSH

# Navigate to your Laravel root directory (where public_html is)
cd /path/to/your/laravel/project

# Remove existing link if it exists
rm -f public/storage

# Create the symbolic link
ln -s ../storage/app/public public/storage

# Set proper permissions
chmod -R 775 storage/app/public
chown -R www-data:www-data storage/app/public 2>/dev/null || true

echo "Storage link created successfully!"
echo "Link: public/storage -> storage/app/public"

