<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        body, h2, a {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* Add background image */
        body {
            background: url('images/dashboard-background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 800px;
            width: 90%;
        }

        h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #007bff;
        }

        .categories {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .category-box {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-box:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .category-name {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            text-align: center;
            padding: 8px;
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .category-box:hover .category-name {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            color: #fff;
            background-color: #007bff;
            text-align: center;
            text-decoration: none;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn.logout {
            background-color: #dc3545;
        }

        .btn.logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select Doctor Category</h2>
        <div class="categories">
            <a href="book_appointment.php?category=Dental Doctor" class="category-box">
                <img src="images/dental_doctor.jpg" alt="Dental Doctor">
                <div class="category-name">Dental Doctor</div>
            </a>
            <a href="book_appointment.php?category=Eye Doctor" class="category-box">
                <img src="images/eye_doctor.jpg" alt="Eye Doctor">
                <div class="category-name">Eye Doctor</div>
            </a>
            <a href="book_appointment.php?category=Neurologist Doctor" class="category-box">
                <img src="images/neurologist_doctor.jpg" alt="Neurologist Doctor">
                <div class="category-name">Neurologist Doctor</div>
            </a>
            <a href="book_appointment.php?category=Cardiology Doctor" class="category-box">
                <img src="images/cardiology_doctor.jpg" alt="Cardiology Doctor">
                <div class="category-name">Cardiology Doctor</div>
            </a>
            <a href="book_appointment.php?category=Pediatrics Doctor" class="category-box">
                <img src="images/pediatrics_doctor.jpg" alt="Pediatrics Doctor">
                <div class="category-name">Pediatrics Doctor</div>
            </a>
            <a href="book_appointment.php?category=Orthopedics Doctor" class="category-box">
                <img src="images/orthopedics_doctor.jpg" alt="Orthopedics Doctor">
                <div class="category-name">Orthopedics Doctor</div>
            </a>
        </div>
        <a href="view_queue.php" class="btn">View Queue</a>
        <a href="index.php" class="btn logout">Logout</a>
    </div>
</body>
</html>
