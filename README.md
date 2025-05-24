# NoteTone

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

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/yourusername/notetone.git
    ```

2. Navigate to the project directory:
    ```bash
    cd notetone
    ```

3. Set up the database:
    - Import the `notetone_db.sql` file into your MySQL database.

4. Configure the database connection:
    - Update the `db.php` file with your database credentials.

5. Start your web server (e.g., using XAMPP or MAMP).

6. Open your browser and navigate to the project directory.

## Usage

### User Registration and Login

- Navigate to the registration page to create a new account.
- After registration, you can log in with your credentials.

### Dashboard

- The dashboard displays all notations and threads in the system.
- Use the dropdown filters to filter notations by creator, song, or instrument.

### Creating Notations and Threads

- Navigate to the notations or threads page to create new entries.
- Provide the required information and submit the form.

### Profile Management

- Navigate to the profile page to change your username or password.
- You can also delete your profile from the profile page.

### Deleting Notations and Threads

- Only the creator of a notation or thread can delete it.
- Navigate to the details page of the notation or thread to delete it.

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
