# Fix Storage Link - Step by Step Guide

## Problem
The `exec()` function is disabled on your server, so `php artisan storage:link` doesn't work.

## Solution: Create the symlink manually

### Step 1: Check your directory structure
```bash
# You're currently in: lauparadiseadventure.com
pwd
ls -la

# Check if public_html exists (might be your public directory)
ls -la public_html

# Check if storage exists
ls -la storage
```

### Step 2: Determine the correct paths

**If `public_html` is your public directory:**
```bash
# From lauparadiseadventure.com directory
ln -s ../storage/app/public public_html/storage
```

**If `public` is your public directory:**
```bash
# From lauparadiseadventure.com directory  
ln -s storage/app/public public/storage
```

**If you need to go to a different location:**
```bash
# Find where your Laravel app is
find . -name "artisan" -type f 2>/dev/null
```

### Step 3: Create the storage directory if it doesn't exist
```bash
# Make sure storage/app/public exists
mkdir -p storage/app/public
chmod -R 775 storage/app/public
```

### Step 4: Create the symlink
```bash
# Remove existing broken link if any
rm -f public/storage
rm -f public_html/storage

# Create the link (adjust path based on your structure)
ln -s ../storage/app/public public/storage
# OR
ln -s ../storage/app/public public_html/storage
```

### Step 5: Verify the link
```bash
ls -la public/storage
# Should show: lrwxrwxrwx ... public/storage -> ../storage/app/public
```

### Step 6: Test
Visit your profile page and try uploading an avatar. The image should now display correctly.


