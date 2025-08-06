# Дипломный проект по профессии «Веб-разработчик»

В проекте используется:
- Laravel + Vue Starter Kit: Vue 3 and the Composition API, TypeScript, Tailwind, and the shadcn-vue component library. [https://github.com/laravel/vue-starter-kit] [https://laravel.com/docs/12.x/starter-kits]
- Для qr-code: endroid/qr-code  
- PHP 8.4.8 (cli) (built: Jun  3 2025 17:34:25) (NTS Visual C++ 2022 x64)

### Запуск проекта
Для запуска нужно выполнить следующие команды:  
-  клонируем рекозиторий: git clone ...
-  открыть директорию проекта и открыть терминал с этим путём
-  через PowerShell установить PHP, Composer и установщик Laravel
Для этого выполнить команду в терминале проекта:  
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
- открыть терминал в директории проекта "neto-web-diplom"
- выполнить команду: composer install
- выполнить команду: composer global require laravel/installer
- в терминале перейти в директорию "cd kinozal-dip" или открыть терминал от туда
- выполнить команду: npm install 
- выполнить команду: cp .env.example .env
- выполнить команду: php artisan key:generate
- выполнить команду: composer install
- выполнить команду: php artisan migrate --seed 
- Are you sure you want to run this command? (yes/no) [no] yes
- Would you like to create it? (yes/no) [yes] yes
- (по желанию) выполнить команду: npm run build 
- выполнить команду: composer run dev
- ( как альтернатива, можно выполнить вместо composer run dev следующие команды:
- выполнить команду: npm run dev
- в другом терменали этой же директории выполнить команду: php artisan serve
- )
