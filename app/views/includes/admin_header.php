<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title><?php echo isset($data['title']) ? $data['title'] : 'Administration Loove'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo BASEURL; ?>/img/favicon.png" type="image/png">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?php echo BASEURL; ?>/css/admin.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        
        .admin-dashboard {
            min-height: 100vh;
            padding: 2rem;
        }
        
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .dashboard-header {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .dashboard-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #6366F1;
            margin: 0;
        }
        
        .dashboard-header p {
            color: #6b7280;
            margin: 0.5rem 0 0;
        }
        
        .admin-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-admin {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            background: #6366F1;
            color: white;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }
        
        .btn-admin.btn-danger {
            background: #EF4444;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #6366F1;
        }
        
        .stat-card.revenue::before {
            background: #10B981;
        }
        
        .stat-card.subscriptions::before {
            background: #F59E0B;
        }
        
        .stat-card.total-revenue::before {
            background: #EF4444;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: #6366F1;
        }
        
        .stat-card.revenue .stat-icon {
            background: #10B981;
        }
        
        .stat-card.subscriptions .stat-icon {
            background: #F59E0B;
        }
        
        .stat-card.total-revenue .stat-icon {
            background: #EF4444;
        }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 0.25rem;
            transition: transform 0.3s ease;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .stat-change {
            font-size: 0.875rem;
            color: #10B981;
            margin-top: 0.25rem;
        }
        
        .quick-nav {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .nav-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .nav-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .nav-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366F1, #10B981);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }
        
        .nav-card h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }
        
        .nav-card p {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .chart-container {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .chart-container h3 {
            margin-bottom: 1.5rem;
            color: #1f2937;
            font-size: 1.25rem;
        }
        
        .recent-activities {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
        }
        
        .activity-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .activity-section h3 {
            margin-bottom: 1.5rem;
            color: #1f2937;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.02);
        }
        
        .activity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .activity-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .activity-avatar.subscription {
            background: #F59E0B;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            color: #1f2937;
        }
        
        .activity-meta {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .activity-time {
            font-size: 0.75rem;
            color: #6b7280;
        }
        
        .activity-actions {
            flex-shrink: 0;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            background: #6366F1;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge.success {
            background: #10B981;
            color: white;
        }
        
        @media (max-width: 768px) {
            .admin-dashboard {
                padding: 1rem;
            }
            
            .dashboard-header {
                flex-direction: column;
                text-align: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .recent-activities {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
