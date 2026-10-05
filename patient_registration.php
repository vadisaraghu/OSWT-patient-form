<?php
// Patient Registration Form - single file (form + display)

$errors = [];
$data = [
    'name'     => '',
    'age'      => '',
    'gender'   => '',
    'email'    => '',
    'mobile'   => '',
    'address'  => '',
    'symptoms' => '',
];
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and trim input
    foreach ($data as $key => $value) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    // Validation
    if ($data['name'] === '' || !preg_match("/^[A-Za-z\s.'-]{2,60}$/", $data['name'])) {
        $errors['name'] = 'Enter a valid name (letters and spaces only).';
    }
    if (!ctype_digit($data['age']) || (int)$data['age'] < 0 || (int)$data['age'] > 120) {
        $errors['age'] = 'Enter a valid age between 0 and 120.';
    }
    if (!in_array($data['gender'], ['Male', 'Female', 'Other'], true)) {
        $errors['gender'] = 'Please select a gender.';
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if (!preg_match('/^[6-9][0-9]{9}$/', $data['mobile'])) {
        $errors['mobile'] = 'Enter a valid 10-digit mobile number.';
    }
    if ($data['address'] === '') {
        $errors['address'] = 'Address is required.';
    }
    if ($data['symptoms'] === '') {
        $errors['symptoms'] = 'Please describe the symptoms.';
    }

    if (!$errors) {
        $submitted = true;
    }
}

// Helper for safe output
function e(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient Registration</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #eef3f8;
            margin: 0;
            padding: 24px 16px;
            color: #1f2a37;
        }
        .card {
            max-width: 560px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .08);
        }
        h1 { margin: 0 0 4px; font-size: 1.5rem; color: #0b5cab; }
        .sub { margin: 0 0 20px; color: #6b7280; font-size: .9rem; }
        label { display: block; margin: 14px 0 6px; font-weight: 600; font-size: .9rem; }
        input[type=text], input[type=number], input[type=email], input[type=tel], textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
        }
        input:focus, textarea:focus { outline: 2px solid #0b5cab55; border-color: #0b5cab; }
        textarea { min-height: 90px; resize: vertical; }
        .radios label { display: inline-block; margin-right: 18px; font-weight: 400; }
        .error { color: #b91c1c; font-size: .8rem; margin-top: 4px; }
        button, .btn {
            display: inline-block;
            margin-top: 22px;
            padding: 11px 22px;
            background: #0b5cab;
            color: #fff;
            border: 0;
            border-radius: 8px;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        button:hover, .btn:hover { background: #094a8c; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { text-align: left; padding: 10px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        th { width: 36%; color: #475569; font-weight: 600; }
        .success { background: #ecfdf5; color: #065f46; padding: 10px 14px; border-radius: 8px; margin-top: 12px; }
    </style>
</head>
<body>
<div class="card">

<?php if ($submitted): ?>

    <h1>Registration Successful</h1>
    <div class="success">Patient details have been submitted.</div>

    <table>
        <tr><th>Patient Name</th><td><?= e($data['name']) ?></td></tr>
        <tr><th>Age</th><td><?= e($data['age']) ?></td></tr>
        <tr><th>Gender</th><td><?= e($data['gender']) ?></td></tr>
        <tr><th>Email</th><td><?= e($data['email']) ?></td></tr>
        <tr><th>Mobile Number</th><td><?= e($data['mobile']) ?></td></tr>
        <tr><th>Address</th><td><?= nl2br(e($data['address'])) ?></td></tr>
        <tr><th>Symptoms</th><td><?= nl2br(e($data['symptoms'])) ?></td></tr>
    </table>

    <a class="btn" href="<?= e($_SERVER['PHP_SELF']) ?>">Register Another Patient</a>

<?php else: ?>

    <h1>Patient Registration</h1>
    <p class="sub">Fill in the details below to register a patient.</p>

    <form method="post" action="<?= e($_SERVER['PHP_SELF']) ?>" novalidate>

        <label for="name">Patient Name</label>
        <input type="text" id="name" name="name" value="<?= e($data['name']) ?>">
        <?php if (isset($errors['name'])): ?><div class="error"><?= e($errors['name']) ?></div><?php endif; ?>

        <label for="age">Age</label>
        <input type="number" id="age" name="age" min="0" max="120" value="<?= e($data['age']) ?>">
        <?php if (isset($errors['age'])): ?><div class="error"><?= e($errors['age']) ?></div><?php endif; ?>

        <label>Gender</label>
        <div class="radios">
            <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                <label>
                    <input type="radio" name="gender" value="<?= $g ?>" <?= $data['gender'] === $g ? 'checked' : '' ?>>
                    <?= $g ?>
                </label>
            <?php endforeach; ?>
        </div>
        <?php if (isset($errors['gender'])): ?><div class="error"><?= e($errors['gender']) ?></div><?php endif; ?>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= e($data['email']) ?>">
        <?php if (isset($errors['email'])): ?><div class="error"><?= e($errors['email']) ?></div><?php endif; ?>

        <label for="mobile">Mobile Number</label>
        <input type="tel" id="mobile" name="mobile" maxlength="10" value="<?= e($data['mobile']) ?>">
        <?php if (isset($errors['mobile'])): ?><div class="error"><?= e($errors['mobile']) ?></div><?php endif; ?>

        <label for="address">Address</label>
        <textarea id="address" name="address"><?= e($data['address']) ?></textarea>
        <?php if (isset($errors['address'])): ?><div class="error"><?= e($errors['address']) ?></div><?php endif; ?>

        <label for="symptoms">Symptoms</label>
        <textarea id="symptoms" name="symptoms"><?= e($data['symptoms']) ?></textarea>
        <?php if (isset($errors['symptoms'])): ?><div class="error"><?= e($errors['symptoms']) ?></div><?php endif; ?>

        <button type="submit">Register Patient</button>
    </form>

<?php endif; ?>

</div>
</body>
</html>
