#!/bin/bash
# Inventory Control System - Quick Setup Script

echo "================================"
echo "Inventory Control System Setup"
echo "================================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "📋 Creating .env file..."
    cp .env.example .env
    echo "✓ .env created"
else
    echo "✓ .env already exists"
fi

# Generate app key
echo ""
echo "🔑 Generating app key..."
php artisan key:generate

# Install composer dependencies
echo ""
echo "📦 Installing PHP dependencies..."
composer install

# Install npm dependencies
echo ""
echo "🎨 Installing JS dependencies..."
npm install

# Build assets
echo ""
echo "🏗️  Building assets..."
npm run build

# Run migrations
echo ""
echo "🗄️  Running migrations..."
php artisan migrate

# Seed database
echo ""
echo "🌱 Seeding database..."
php artisan db:seed

echo ""
echo "================================"
echo "✅ Setup Complete!"
echo "================================"
echo ""
echo "🚀 Start the server with:"
echo "   php artisan serve"
echo ""
echo "📧 Test Credentials:"
echo "   Admin:  admin@inventory.test / password"
echo "   Kasir1: cashier1@inventory.test / password"
echo "   Kasir2: cashier2@inventory.test / password"
echo ""
echo "📖 Documentation available in:"
echo "   - SETUP_GUIDE.md"
echo "   - API_DOCUMENTATION.md"
echo "   - IMPLEMENTATION_SUMMARY.md"
echo ""
