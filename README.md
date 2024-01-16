
```markdown
# Task API

Brief description of your project.

## Prerequisites

- PHP 8
- Composer
- Laravel
- MySQL (or your preferred database)
- [Postman](https://www.postman.com/) for testing API endpoints

## Installation

1. Clone the repository:
   ```
   ```bash
   git clone https://github.com/youngyusuff6/task_api.git
   ```

2. Navigate to the project directory:

   ```bash
   cd task_api
   ```

3. Install dependencies:

   ```bash
   composer install
   ```

4. Copy the `.env.example` file to `.env` and configure your environment variables, including:

   - `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - `STRIPE_PUBLISHER_KEY`
   - `STRIPE_SECRET_KEY`
   - `STRIPE_CURRENCY`

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Run migrations:

   ```bash
   php artisan migrate
   ```

7. Install Passport:

   ```bash
   php artisan passport:install
   ```

   Follow the instructions to generate encryption keys.

8. Run the development server:

   ```bash
   php artisan serve
   ```

   Your application should now be accessible at http://localhost:8000.

## Usage

1. Open Postman and import the [API collection](https://documenter.getpostman.com/view/19899859/2s9YsQ8q9V).

2. Update the environment variables in Postman:

   - `base_url`: Set it to the URL where your Laravel app is running (e.g., http://localhost:8000).

3. Test the provided API endpoints in Postman.

## API Documentation

Detailed documentation for the API endpoints can be found in the [Postman collection](https://documenter.getpostman.com/view/19899859/2s9YsQ8q9V).

```
