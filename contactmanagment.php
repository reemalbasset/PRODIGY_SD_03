<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="contactmanagment.css">
    <title>Contact Management System</title>
</head>
<body>

<h2>Contact Management System</h2>
<div class="container">
<form method="post" action="">
    <label for="name">Name:</label>
    <input type="text" name="name" required><br>

    <label for="phone">Phone:</label>
    <input type="tel" name="phone" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" required><br>

    <input type="submit" name="add" value="Add Contact">
</form>

<?php
if (!file_exists('contacts.txt')) {
    file_put_contents('contacts.txt', '');
}

function readContactsFromFile() {
    $contents = file_get_contents('contacts.txt');
    return json_decode($contents, true) ?: [];
}

function saveContactsToFile($contacts) {
    file_put_contents('contacts.txt', json_encode($contacts));
}

$contacts = readContactsFromFile();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];

        $contact = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email
        ];

        $contacts[] = $contact;
        saveContactsToFile($contacts);
        echo "Contact added successfully.";
    } elseif (isset($_POST["view"])) {
        echo "<h3>Contact List:</h3>";
        echo "<ul>";
        foreach ($contacts as $key => $contact) {
            echo "<li>{$contact['name']} - Phone: {$contact['phone']}, Email: {$contact['email']}";
            echo " <form style='display:inline;' method='post' action=''>
                      <input type='hidden' name='edit_key' value='{$key}'>
                      <input type='submit' name='edit' value='Edit'>
                  </form>";
            echo " <form style='display:inline;' method='post' action=''>
                      <input type='hidden' name='delete_key' value='{$key}'>
                      <input type='submit' name='delete' value='Delete'>
                  </form>";
            echo "</li>";
        }
        echo "</ul>";
    } elseif (isset($_POST["edit"])) {
        $editKey = $_POST["edit_key"];
        $editContact = $contacts[$editKey];
        echo "<h3>Edit Contact:</h3>";
        echo "<form method='post' action=''>
                <label for='name'>Name:</label>
                <input type='text' name='edited_name' value='{$editContact['name']}' required><br>
                
                <label for='phone'>Phone:</label>
                <input type='tel' name='edited_phone' value='{$editContact['phone']}' required><br>
                
                <label for='email'>Email:</label>
                <input type='email' name='edited_email' value='{$editContact['email']}' required><br>
                
                <input type='hidden' name='edit_key' value='{$editKey}'>
                <input type='submit' name='save_edit' value='Save Changes'>
            </form>";
    } elseif (isset($_POST["save_edit"])) {
        $editKey = $_POST["edit_key"];
        $editedName = $_POST["edited_name"];
        $editedPhone = $_POST["edited_phone"];
        $editedEmail = $_POST["edited_email"];

        $contacts[$editKey] = [
            'name' => $editedName,
            'phone' => $editedPhone,
            'email' => $editedEmail
        ];

        saveContactsToFile($contacts);
        echo "Contact edited successfully.";
    } elseif (isset($_POST["delete"])) {
        $deleteKey = $_POST["delete_key"];
        unset($contacts[$deleteKey]);
        $contacts = array_values($contacts); // Reindex array after deletion of contact
        saveContactsToFile($contacts);
        echo "Contact deleted successfully.";
    }
}
?>
<br><br>
<form method="post" action="">
<input type="submit" name="view" value="View Contacts">
</form>
</div>
</body>
</html>

