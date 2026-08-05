<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy - <?= html_escape($developer_name); ?></title>
    <style>
        body {
            margin: 0;
            padding: 24px 16px;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
            line-height: 1.6;
            background: #fff;
        }
        .container {
            max-width: 860px;
            margin: 0 auto;
        }
        h1, h2 {
            margin: 0 0 12px 0;
            line-height: 1.3;
        }
        h2 {
            margin-top: 24px;
            font-size: 20px;
        }
        p, li {
            font-size: 16px;
        }
        ul {
            padding-left: 20px;
        }
        .meta {
            margin-bottom: 18px;
            color: #555;
            font-size: 14px;
        }
        a {
            color: #0a58ca;
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>Privacy Policy</h1>
        <p class="meta"><strong>Last updated:</strong> <?= html_escape($last_updated); ?></p>
        <p>
            This Privacy Policy explains how <strong><?= html_escape($developer_name); ?></strong> collects, uses,
            stores, and shares personal data when you use the Kiustore application and related services.
        </p>

        <h2>1. Data We Collect</h2>
        <ul>
            <li>Account information: name, email address, phone number, and login credentials.</li>
            <li>Profile and shipping data: billing/shipping address and recipient details.</li>
            <li>Transaction data: order details, purchased products, payment status, invoice information.</li>
            <li>Technical data: IP address, browser/app version, device information, usage logs.</li>
            <li>Communication data: messages, support requests, and feedback you send through our service.</li>
            <li>Location-related data that you provide for delivery cost calculation and shipment processing.</li>
        </ul>

        <h2>2. How We Use Data</h2>
        <ul>
            <li>To create and manage user accounts.</li>
            <li>To process orders, payments, shipping, and customer support.</li>
            <li>To provide features and improve application performance and security.</li>
            <li>To communicate service updates, transaction notifications, and support responses.</li>
            <li>To comply with legal obligations and prevent fraud or abuse.</li>
        </ul>

        <h2>3. How We Share Data</h2>
        <p>We may share data only when necessary with:</p>
        <ul>
            <li>Payment service providers to process transactions.</li>
            <li>Shipping and logistics partners to fulfill deliveries.</li>
            <li>Technology providers (hosting, infrastructure, analytics, and support tools).</li>
            <li>Government/regulatory authorities when required by law.</li>
        </ul>
        <p>We do not sell your personal data to third parties.</p>

        <h2>4. Data Retention and Storage</h2>
        <ul>
            <li>We retain personal data for as long as needed to provide services and meet legal requirements.</li>
            <li>Data is stored on secured systems with access controls.</li>
            <li>When retention is no longer required, data is deleted or anonymized where feasible.</li>
        </ul>

        <h2>5. Security Measures</h2>
        <p>
            We implement reasonable technical and organizational safeguards to protect data from unauthorized access,
            alteration, disclosure, or destruction.
        </p>

        <h2>6. Your Rights</h2>
        <p>
            You may request access, correction, or deletion of your personal data by contacting us through the
            contact details below.
        </p>

        <h2>7. Children&apos;s Privacy</h2>
        <p>
            Our services are not intended for children under the applicable minimum age. We do not knowingly collect
            personal data from children without appropriate consent.
        </p>

        <h2>8. Changes to This Policy</h2>
        <p>
            We may update this Privacy Policy from time to time. Any changes will be posted on this page with an
            updated effective date.
        </p>

        <h2>9. Developer and Contact Information</h2>
        <p><strong>Developer/Company:</strong> <?= html_escape($developer_name); ?></p>
        <?php if (!empty($store_email)) : ?>
            <p><strong>Email:</strong> <a href="mailto:<?= html_escape($store_email); ?>"><?= html_escape($store_email); ?></a></p>
        <?php endif; ?>
        <?php if (!empty($store_phone)) : ?>
            <p><strong>Phone:</strong> <?= html_escape($store_phone); ?></p>
        <?php endif; ?>
        <?php if (!empty($store_address)) : ?>
            <p><strong>Address:</strong> <?= nl2br(html_escape($store_address)); ?></p>
        <?php endif; ?>
        <p><strong>Contact page:</strong> <a href="<?= site_url('contact'); ?>"><?= site_url('contact'); ?></a></p>
    </main>
</body>
</html>
