# Deployment on Render.com

## 1. Prerequisites
- Push your code to a GitHub repository.
- Create a Render.com account.

## 2. Prepare Environment Variables
- Copy `.env.example` to `.env` locally and fill in secrets.
- On Render, add all variables from `.env.example` to the Environment tab (set secrets and DB credentials).
- Generate an `APP_KEY` locally with `php artisan key:generate` and copy it to Render.

## 3. Set Up MySQL Database
- On Render, create a Managed MySQL instance.
- Copy the DB credentials to your web service's environment variables.

## 4. Create a New Web Service
- Connect your GitHub repo to Render.
- Select "Web Service" and choose PHP environment.
- Set the following:
  - **Build Command:**
    ```
    composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan migrate --force
    ```
  - **Start Command:**
    ```
    php artisan serve --host 0.0.0.0 --port $PORT
    ```
  - **Root Directory:** `.`
  - **Publish Directory:** `public`

## 5. Deploy
- Trigger a deploy on Render.
- Check logs for errors.
- Visit your Render URL to test the app.

## 6. Notes
- Set `APP_DEBUG=false` in production.
- For file uploads, use the `public` disk or configure S3.
- For caching/queue, use Redis or database. 