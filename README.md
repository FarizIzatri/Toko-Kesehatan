How to Run the Project

1. Initial Setup

After cloning the repository, run the following commands to install all required dependencies:

    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
    npm install
    npm run build

All of the above steps are already included in a single script:

    composer run setup

2. Development Mode

To run the Laravel server, queue listener, and Vite in parallel, use:

    composer run dev
    
This command automatically starts:

    php artisan serve
    php artisan queue:listen
    npm run dev (Vite)

3. Front-end Production Build

To generate optimized front-end assets using Vite:

    npm run build


(Or use the equivalent command if you’re using pnpm or yarn.)

4. Testing & Maintenance

Run the full test suite and clear config cache:

    composer run test


You can also use standard Laravel operational commands as needed, such as:

    php artisan queue:work
    php artisan schedule:run
