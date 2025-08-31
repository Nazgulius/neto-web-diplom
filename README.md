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
- *как альтернатива, можно выполнить вместо composer run dev следующие команды:
- выполнить команду: npm run dev
- в другом терменали этой же директории выполнить команду: php artisan serve
  
  
### Использование проекта
**Пользователь**
Пользователь может: 
1) выбрать кино; 
2) выбрать место; 
3) убедиться в своём выборе; 
4) получить qr-код для входа в зал.

**Администратор**
Для перехода в административную часть, на начальном этапе разработки, нужно вводить тестовые данные: test@example.com и пароль test.
Администратор может:
1) добавить/удалить зал для просмотра кино:
   - для создания нажать кнопку "Создать зал", заполнить форму, нажать кнопку "Create Movie";
   - для удаления нажать кнопку в виде корзины, напротив не нужного зала;
2) добавить/удалить кино:
   - нажать кнопку "Добавить фильм", заполнить поля, нажать кнопку "Create Movie";
   - для удаления нажать на искомое кино, затем на кнопку "Удалить кино" или в виде корзины;
3) добавить/удалить сеанс, на котором можно посмотреть кино:
   - для добавления нужно нажать на искомое кино, затем на кнопку "Add session movie", заполнить форму, нажать на кнопку "Add session movie";
   - для удаления нажать на искомое кино, затем нажать кнопку в виде корзины, напротив не нужного зала (Удаление так же доступно и при добавлении сессий);
4) изменить цену билетов; (в процессе)
5) изменить ряды и места в зале; (в процессе)
6) открывать/закрывать продажу билетов.



