# NoteTone - Mūzikas notāciju un diskusiju platforma

NoteTone is a web application designed for music enthusiasts to share, view, and manage musical notations and threads. Users can create accounts, log in, create notations and threads.

## Features

- User registration and login
- Dashboard displaying all notations and threads
- Create, view, and delete notations
- Create, view, and delete threads
- Profile management with options to change username and password

## Technologies Used

- HTML5, CSS3, JavaScript
- PHP
- MySQL

## Database Schema

The database contains the following tables:
- `users`: Stores user information
- `notations`: Stores musical notations
- `threads`: Stores discussion threads
- `songs`: Stores song information
- `instruments`: Stores instrument information
- `threadcomments`: Stores comments on threads (not used in this version)
- `videocomments`: Stores comments on videos (not used in this version)
- `videos`: Stores video information (not used in this version)

## Sistēmas prasības
- PHP 7.4 vai jaunāka versija
- MySQL 5.7 vai jaunāka versija
- XAMPP vai līdzīgs lokālais serveris
- Node.js un npm (jaunākā versija)

## Instalācijas norādījumi

### 1. Servera iestatīšana
1. Lejupielādējiet un instalējiet XAMPP no [oficiālās mājaslapas](https://www.apachefriends.org/)
2. Palaidiet XAMPP Control Panel
3. Startējiet Apache un MySQL servisus

### 2. Projekta instalācija
1. Klonējiet repozitoriju vai lejupielādējiet projekta failus
2. Novietojiet projekta failus XAMPP htdocs mapē (parasti `C:\xampp\htdocs\notetone`)
3. Atveriet phpMyAdmin (http://localhost/phpmyadmin)
4. Izveidojiet jaunu datubāzi ar nosaukumu `notetone_db`
5. Importējiet datubāzes struktūru no `database/notetone_db.sql` faila

### 3. Projekta konfigurācija
1. Atveriet `includes/db.php` failu
2. Pārliecinieties, ka datubāzes piekļuves dati ir pareizi:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "notetone_db";
   ```

### 4. Frontend atkarību instalācija
1. Atveriet termināli projekta mapē
2. Izpildiet komandu:
   ```bash
   npm install
   ```

## Sistēmas palaišana

1. Palaidiet XAMPP Control Panel
2. Startējiet Apache un MySQL servisus
3. Atveriet pārlūkprogrammu un dodieties uz:
   ```
   http://localhost/notetone
   ```

## Pirmā lietotāja reģistrācija

1. Atveriet http://localhost/notetone/register.php
2. Aizpildiet reģistrācijas formu
3. Pēc reģistrācijas varat pieteikties sistēmā

## Administratora piekļuve

1. Piesakieties sistēmā ar administratora kontu
2. Administratora panelis ir pieejams caur:
   ```
   http://localhost/notetone/admin_panel.php
   ```

## Problēmu risināšana

Ja rodas problēmas ar datubāzes savienojumu:
1. Pārbaudiet, vai MySQL serviss ir aktīvs
2. Pārbaudiet datubāzes piekļuves datus `includes/db.php` failā
3. Pārliecinieties, ka datubāze `notetone_db` eksistē

Ja rodas problēmas ar frontend:
1. Pārbaudiet, vai visas npm atkarības ir instalētas
2. Pārbaudiet pārlūkprogrammas konsoleļa kļūdas

## Papildu informācija

- Sistēma izmanto PHP sesijas lietotāju autentifikācijai
- Failu augšupielādei tiek izmantota `assets/uploads` mape
- Sistēma atbalsta dažādus mūzikas failu formātus

## Project Structure

- `index.html`: The landing page
- `login.php`: The login page
- `register.php`: The registration page
- `dashboard.php`: The main dashboard page
- `notations.php`: The notations management page
- `threads.php`: The threads management page
- `profile.php`: The user profile management page
- `add_notation.php`: The page for adding a new notation
- `add_thread.php`: The page for adding a new thread
- `view_notation.php`: The page for viewing a specific notation
- `view_thread.php`: The page for viewing a specific thread
- `db.php`: The database connection script
- `style.css`: The main stylesheet

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -am 'Add new feature'`).
5. Push to the branch (`git push origin feature-branch`).
6. Create a new Pull Request.
