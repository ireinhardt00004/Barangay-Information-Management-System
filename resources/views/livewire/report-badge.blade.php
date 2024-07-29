<div>
    @if($count > 0)
        <div style="
            position: relative;
            display: inline-block;
            padding: 10px;
        ">
            <span style="
                position: absolute;
                top: -10px;
                right: -10px;
                background-color: red;
                color: white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: bold;
            ">
                {{ $count }}
            </span>   
        </div>
    @endif
</div>
