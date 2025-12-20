<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SAFRECO | Impression</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        background: #f5f5f5;
        line-height: 1.6;
    }

    @media print {
        body {
            background: white;
            margin: 0;
            padding: 0;
        }

        .no-print {
            display: none !important;
        }

        .print-container {
            margin: 0;
            padding: 20px;
            box-shadow: none;
            border: none;
        }

        .header {
            border-radius: 0;
            box-shadow: none;
        }
    }

    .print-container {
        max-width: 850px;
        margin: 20px auto;
        padding: 30px;
        background: white;
        border: 1px solid #ddd;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* En-tête personnalisé avec infos entete */
    .header {
        background: linear-gradient(135deg, #1a3a6b 0%, #2563eb 50%, #0d6efd 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 8px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 30px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .header-logo {
        flex-shrink: 0;
        width: 90px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        overflow: hidden;
    }

    .header-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .header-info {
        flex-grow: 1;
        border-right: 2px solid rgba(255, 255, 255, 0.2);
        padding-right: 30px;
    }

    .header-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .header-subtitle {
        font-size: 14px;
        opacity: 0.95;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .header-description {
        font-size: 12px;
        opacity: 0.9;
        margin-bottom: 12px;
        line-height: 1.5;
    }

    .header-contact {
        font-size: 11px;
        opacity: 0.85;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .header-contact-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .header-contact-item::before {
        content: "•";
        opacity: 0.6;
    }

    .header-receipt {
        flex-shrink: 0;
        text-align: right;
    }

    .header-receipt-no {
        font-size: 14px;
        opacity: 0.95;
        margin-bottom: 5px;
    }

    .header-receipt-id {
        font-size: 24px;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 12px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
    }

    .receipt-date {
        font-size: 11px;
        opacity: 0.85;
        margin-top: 8px;
    }

    .document-title {
        text-align: center;
        font-size: 22px;
        font-weight: 700;
        margin: 30px 0;
        color: #1a3a6b;
        border-bottom: 4px solid #2563eb;
        border-top: 4px solid #2563eb;
        padding: 12px 0;
        background-color: #f8f9fa;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-section {
        margin-bottom: 25px;
    }

    .info-section-title {
        background-color: #f8f9fa;
        border-left: 5px solid #2563eb;
        padding: 10px 15px;
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 15px;
        text-transform: uppercase;
        color: #1a3a6b;
    }

    .info-row {
        display: flex;
        margin-bottom: 10px;
        border-bottom: 1px dotted #ddd;
        padding-bottom: 8px;
    }

    .info-label {
        font-weight: 600;
        width: 35%;
        color: #555;
        font-size: 13px;
    }

    .info-value {
        width: 65%;
        text-align: left;
        color: #333;
        font-size: 13px;
    }

    .two-columns {
        display: flex;
        gap: 30px;
        margin-bottom: 25px;
    }

    .column {
        flex: 1;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 13px;
    }

    .table thead {
        background-color: #f8f9fa;
        border-top: 2px solid #333;
        border-bottom: 2px solid #333;
    }

    .table th {
        padding: 12px;
        text-align: left;
        font-weight: 700;
        color: #1a3a6b;
    }

    .table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }

    .table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .amount-section {
        margin-top: 25px;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        border: 2px solid #2563eb;
        border-radius: 6px;
    }

    .amount-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
        padding: 8px 0;
        border-bottom: 1px dotted #ddd;
    }

    .amount-row.total {
        font-weight: 700;
        font-size: 16px;
        background-color: rgba(37, 99, 235, 0.1);
        border-bottom: 2px solid #2563eb;
        padding: 12px 0;
        margin-bottom: 0;
        border-radius: 4px;
    }

    .amount-label {
        font-weight: 600;
        color: #555;
    }

    .amount-value {
        font-weight: 700;
        color: #1a3a6b;
        text-align: right;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-primary {
        background-color: #2563eb;
        color: white;
    }

    .status-badge {
        padding: 10px 15px;
        border-radius: 4px;
        font-weight: 700;
        font-size: 13px;
        display: inline-block;
    }

    .status-paid {
        background-color: #d4edda;
        color: #155724;
    }

    .status-partial {
        background-color: #fff3cd;
        color: #856404;
    }

    .footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 2px solid #ddd;
        text-align: center;
        font-size: 11px;
        color: #999;
    }

    .signature-section {
        display: flex;
        justify-content: space-around;
        margin-top: 50px;
        text-align: center;
    }

    .signature-box {
        width: 150px;
        border-top: 2px solid #333;
        padding-top: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .print-buttons {
        text-align: center;
        margin-bottom: 20px;
        gap: 10px;
    }

    .print-buttons button,
    .print-buttons a {
        padding: 10px 20px;
        margin: 5px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }

    .btn-print {
        background-color: #2563eb;
        color: white;
    }

    .btn-print:hover {
        background-color: #1e40af;
    }

    .btn-back {
        background-color: #6c757d;
        color: white;
    }

    .btn-back:hover {
        background-color: #545b62;
    }

    .divider {
        height: 1px;
        background-color: #ddd;
        margin: 20px 0;
    }
</style>
