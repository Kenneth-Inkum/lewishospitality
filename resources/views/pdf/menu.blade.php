<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lewis Hospitality Menu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1f2937;
            line-height: 1.6;
            padding: 30px 40px;
        }
        
        .header {
            text-align: center;
            padding: 20px 0 25px;
            border-bottom: 3px solid #d97706;
            margin-bottom: 35px;
        }
        
        .header h1 {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .category {
            margin-bottom: 35px;
            page-break-inside: avoid;
        }
        
        .category-header {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f59e0b;
        }
        
        .menu-item {
            margin-bottom: 18px;
            padding-bottom: 15px;
            padding-left: 5px;
            border-bottom: 1px solid #e5e7eb;
            page-break-inside: avoid;
        }
        
        .menu-item:last-child {
            border-bottom: none;
        }
        
        .item-header {
            margin-bottom: 5px;
            overflow: hidden;
        }
        
        .item-name {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            float: left;
            max-width: 70%;
        }
        
        .item-featured {
            color: #d97706;
        }
        
        .item-price {
            font-size: 14px;
            font-weight: 600;
            color: #d97706;
            float: right;
        }
        
        .item-tags {
            margin-bottom: 5px;
            clear: both;
        }
        
        .tag {
            display: inline-block;
            font-size: 9px;
            padding: 2px 7px;
            background-color: #fef3c7;
            color: #92400e;
            border-radius: 10px;
            margin-right: 4px;
            text-transform: capitalize;
        }
        
        .item-description {
            font-size: 11px;
            color: #6b7280;
            line-height: 1.5;
            clear: both;
        }
        
        .footer {
            position: fixed;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lewis Hospitality</h1>
        <p>Menu</p>
    </div>

    @foreach ($categories as $category)
        <div class="category">
            <h2 class="category-header">{{ $category->name }}</h2>
            
            @foreach ($category->items as $item)
                <div class="menu-item">
                    <div class="item-header">
                        <span class="item-name {{ $item->featured ? 'item-featured' : '' }}">
                            {{ $item->name }}{{ $item->featured ? ' ★' : '' }}
                        </span>
                        <span class="item-price">${{ number_format($item->price, 2) }}</span>
                    </div>
                    
                    @if ($item->dietary_tags && count($item->dietary_tags) > 0)
                        <div class="item-tags">
                            @foreach ($item->dietary_tags as $tag)
                                <span class="tag">{{ str_replace('_', ' ', $tag) }}</span>
                            @endforeach
                        </div>
                    @endif
                    
                    @if ($item->description)
                        <p class="item-description">{{ $item->description }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="footer">
        <p>Lewis Hospitality • Generated {{ now()->format('F j, Y') }}</p>
    </div>
</body>
</html>
