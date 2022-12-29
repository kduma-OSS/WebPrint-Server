FROM node:19-alpine AS vite_stage

WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm ci

COPY postcss.config.js tailwind.config.js vite.config.js ./
COPY resources ./resources
RUN npm run build






FROM nginx:alpine

WORKDIR /var/www/html

COPY docker/nginx/default.conf /etc/nginx/conf.d

COPY --from=vite_stage /var/www/html/public/build /var/www/html/public/build

COPY ./ /var/www/html/
