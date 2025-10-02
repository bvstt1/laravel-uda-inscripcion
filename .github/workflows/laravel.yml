name: Docker Image CI

on:
  push:
    branches: [ master ]
  pull_request:
    branches: [ master ]

jobs:
  build:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout
        uses: actions/checkout@v2

      - name: Set up QEMU
        uses: docker/setup-qemu-action@master
        with:
          platforms: all

      - name: Login to Docker Hub
        uses: docker/login-action@v1
        with:
          username: ${{ secrets.DOCKER_HUB_USERNAME }}
          password: ${{ secrets.DOCKER_HUB_ACCESS_TOKEN }}

      - name: Set up Docker Buildx
        uses: docker/setup-buildx-action@v1

      - name: Build and push Docker image
        uses: docker/build-push-action@v2
        with:
          context: .
          file: ./Dockerfile
          platforms: linux/amd64,linux/arm64
          push: true
          tags: ${{ secrets.DOCKER_HUB_USERNAME }}/laravel:latest
          no-cache: true

      - name: Check PHP version in built image
        run: docker run --rm ${{ secrets.DOCKER_HUB_USERNAME }}/laravel:latest php -v

      - name: Clear Docker builder cache
        run: docker builder prune -af

      - name: Clear Composer cache
        run: composer clear-cache
