<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.5;
            color: #2c3e50;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            font-size: 14px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #3498db;
            padding-bottom: 25px;
            margin-bottom: 35px;
        }
        
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        .header p {
            color: #7f8c8d;
            margin: 8px 0 0 0;
            font-size: 13px;
            font-weight: normal;
        }
        
        .reservation-details {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 30px;
            margin: 25px 0;
        }
        
        .detail-row {
            border-bottom: 1px solid #dee2e6;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: bold;
            color: #495057;
            width: 45%;
            vertical-align: top;
            padding: 12px 15px 12px 0;
        }
        
        .detail-value {
            color: #212529;
            width: 55%;
            text-align: right;
            vertical-align: top;
            font-weight: normal;
            padding: 12px 0;
        }
        
        .status {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
            border: 2px solid #ffd43b;
        }
        
        .status.confirmed {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #28a745;
        }
        
        .status.cancelled {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }
        
        .restaurant-info {
            background: #ffffff;
            border: 3px solid #3498db;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }
        
        .restaurant-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0 0 12px 0;
            letter-spacing: 0.3px;
        }
        
        .restaurant-details {
            color: #7f8c8d;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .restaurant-details p {
            margin: 6px 0;
        }
        
        .special-requests {
            background: #e8f4fd;
            border-left: 5px solid #3498db;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .special-requests h3 {
            margin: 0 0 12px 0;
            color: #2c3e50;
            font-size: 16px;
            font-weight: bold;
        }
        
        .special-requests p {
            margin: 0;
            color: #34495e;
            line-height: 1.6;
        }
        
        .footer {
            margin-top: 45px;
            padding-top: 25px;
            border-top: 2px solid #ecf0f1;
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
            line-height: 1.5;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .qr-note {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
            font-size: 13px;
            color: #495057;
            line-height: 1.6;
        }
        
        .qr-note p {
            margin: 8px 0;
        }
        
        .qr-note strong {
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reservation Confirmation</h1>
        <p>Generated on {{ date('F j, Y \a\t g:i A') }}</p>
    </div>

    <div class="restaurant-info">
        <div class="restaurant-name">{{ $reservation->restaurant->name }}</div>
        <div class="restaurant-details">
            @if($reservation->restaurant->address)
                <p><strong>Address:</strong> {{ $reservation->restaurant->address }}</p>
            @endif
            @if($reservation->restaurant->phone)
                <p><strong>Phone:</strong> {{ $reservation->restaurant->phone }}</p>
            @endif
            @if($reservation->restaurant->cuisine_type)
                <p><strong>Cuisine:</strong> {{ $reservation->restaurant->cuisine_type }}</p>
            @endif
        </div>
    </div>

    <div class="reservation-details">
        <table style="width: 100%; border-collapse: collapse;">
            <tr class="detail-row">
                <td class="detail-label">Reservation ID:</td>
                <td class="detail-value">#{{ $reservation->id }}</td>
            </tr>
            
            <tr class="detail-row">
                <td class="detail-label">Guest Name:</td>
                <td class="detail-value">{{ $reservation->user->name }}</td>
            </tr>
            
            <tr class="detail-row">
                <td class="detail-label">Date:</td>
                <td class="detail-value">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('F j, Y') }}</td>
            </tr>
            
            <tr class="detail-row">
                <td class="detail-label">Time:</td>
                <td class="detail-value">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('g:i A') }}</td>
            </tr>
            
            <tr class="detail-row">
                <td class="detail-label">Party Size:</td>
                <td class="detail-value">{{ $reservation->party_size }} {{ $reservation->party_size == 1 ? 'person' : 'people' }}</td>
            </tr>
            
            <tr class="detail-row">
                <td class="detail-label">Status:</td>
                <td class="detail-value">
                    <span class="status {{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span>
                </td>
            </tr>
            
            @if($reservation->contact_phone)
            <tr class="detail-row">
                <td class="detail-label">Contact Phone:</td>
                <td class="detail-value">{{ $reservation->contact_phone }}</td>
            </tr>
            @endif
            
            @if($reservation->contact_email)
            <tr class="detail-row">
                <td class="detail-label">Contact Email:</td>
                <td class="detail-value">{{ $reservation->contact_email }}</td>
            </tr>
            @endif
            
            <tr class="detail-row">
                <td class="detail-label">Created:</td>
                <td class="detail-value">{{ $reservation->created_at->format('F j, Y \a\t g:i A') }}</td>
            </tr>
        </table>
    </div>

    @if($reservation->special_requests)
    <div class="special-requests">
        <h3>Special Requests</h3>
        <p>{{ $reservation->special_requests }}</p>
    </div>
    @endif

    <div class="qr-note">
        <p><strong>Please bring this confirmation with you to the restaurant.</strong></p>
        <p>For any changes or cancellations, please contact the restaurant directly or use our online platform.</p>
    </div>

    <div class="footer">
        <p>Thank you for choosing {{ $reservation->restaurant->name }}!</p>
        <p>This document was generated automatically and serves as your reservation confirmation.</p>
    </div>
</body>
</html>