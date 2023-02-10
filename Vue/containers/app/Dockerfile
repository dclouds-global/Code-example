# Builder
FROM node:16-alpine as node-builder
WORKDIR /app
COPY package*.json .env ./
RUN npm ci --prefer-offline
COPY . .
RUN npm run build

# Nginx
FROM nginx:1.20.2-alpine as nginx-runtime
COPY containers/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=node-builder /app/dist /app/
EXPOSE 8080
CMD ["nginx", "-g", "daemon off;"]
