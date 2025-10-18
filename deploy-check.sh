#!/bin/bash

# Deployment Checklist Script for Render.com
# Run this before deploying to ensure everything is ready

echo "🚀 SwordHub Backend - Deployment Checklist"
echo "=========================================="
echo ""

# Check if Docker is installed
echo "✓ Checking Docker..."
if command -v docker &> /dev/null; then
    echo "  ✅ Docker is installed: $(docker --version)"
else
    echo "  ❌ Docker is NOT installed. Please install Docker first."
    echo "     Download from: https://www.docker.com/get-started"
    exit 1
fi

echo ""

# Check if required files exist
echo "✓ Checking required files..."
FILES=(
    "Dockerfile"
    "render.yaml"
    ".htaccess"
    "composer.json"
    "index.php"
    ".dockerignore"
    "docker/000-default.conf"
)

for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "  ✅ $file exists"
    else
        echo "  ❌ $file is missing!"
        exit 1
    fi
done

echo ""

# Check .env file
echo "✓ Checking environment variables..."
if [ -f ".env" ]; then
    echo "  ✅ .env file exists"
    
    # Check required variables
    REQUIRED_VARS=(
        "MONGODB_URI"
        "MONGODB_DATABASE"
        "JWT_SECRET"
        "FRONTEND_URL"
        "CLOUDINARY_CLOUD_NAME"
        "CLOUDINARY_API_KEY"
        "CLOUDINARY_API_SECRET"
    )
    
    for var in "${REQUIRED_VARS[@]}"; do
        if grep -q "^${var}=" .env; then
            echo "  ✅ $var is set"
        else
            echo "  ⚠️  $var is NOT set in .env"
        fi
    done
else
    echo "  ⚠️  .env file not found (this is OK for production)"
fi

echo ""

# Test Docker build (optional)
echo "✓ Testing Docker build..."
read -p "  Do you want to test Docker build locally? (y/n): " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "  Building Docker image..."
    if docker build -t swordhub-backend-test .; then
        echo "  ✅ Docker build successful!"
        echo "  🧹 Cleaning up test image..."
        docker rmi swordhub-backend-test
    else
        echo "  ❌ Docker build failed! Fix errors before deploying."
        exit 1
    fi
fi

echo ""

# Check Git status
echo "✓ Checking Git status..."
if [ -d ".git" ]; then
    echo "  ✅ Git repository detected"
    
    # Check if there are uncommitted changes
    if [ -n "$(git status --porcelain)" ]; then
        echo "  ⚠️  You have uncommitted changes:"
        git status --short
        echo ""
        read -p "  Commit changes now? (y/n): " -n 1 -r
        echo ""
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            git add .
            read -p "  Enter commit message: " commit_msg
            git commit -m "$commit_msg"
            echo "  ✅ Changes committed"
        fi
    else
        echo "  ✅ No uncommitted changes"
    fi
    
    # Check current branch
    BRANCH=$(git rev-parse --abbrev-ref HEAD)
    echo "  📌 Current branch: $BRANCH"
    
    # Offer to push
    echo ""
    read -p "  Push to GitHub? (y/n): " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        git push origin $BRANCH
        echo "  ✅ Pushed to GitHub"
    fi
else
    echo "  ⚠️  Not a Git repository. Initialize with: git init"
fi

echo ""
echo "=========================================="
echo "📋 Deployment Summary"
echo "=========================================="
echo ""
echo "✅ All checks passed! Your backend is ready for deployment."
echo ""
echo "📝 Next Steps:"
echo ""
echo "1. Go to https://render.com"
echo "2. Click 'New +' → 'Blueprint' (if using render.yaml)"
echo "   OR 'New +' → 'Web Service' (for manual setup)"
echo "3. Connect your GitHub repository"
echo "4. Add these environment variables in Render dashboard:"
echo ""
echo "   Required Variables (get from your .env):"
echo "   - MONGODB_URI"
echo "   - JWT_SECRET"
echo "   - FRONTEND_URL (your Vercel URL)"
echo "   - CLOUDINARY_CLOUD_NAME"
echo "   - CLOUDINARY_API_KEY"
echo "   - CLOUDINARY_API_SECRET"
echo ""
echo "5. Click 'Deploy' and wait 5-10 minutes"
echo "6. Test your API at: https://your-service-name.onrender.com"
echo ""
echo "📖 Full guide: See RENDER_DEPLOYMENT.md"
echo ""
echo "🎉 Good luck with your deployment!"
