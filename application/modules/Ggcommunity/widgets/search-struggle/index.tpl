<div class="search_widget row">
    <div class="widget_holder large-12 small-12">
        <div class="logo_holder">
            <div class="logo_image large-1 small-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="77px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"/><stop offset="1" stop-color="#5bc6cd"/></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"/><stop offset="1" stop-color="#51b2b6"/></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="url(#a)"/><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="url(#b)"/><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"/></svg>
            </div>
            <h1 class="logo_name"><?php echo $this->title; ?></h1>
            <p class="logo_description"><?php echo $this->description; ?></p>
        </div>
        <div class="form_holder large-9 small-11">
            <?php echo $this->form->render($this) ?>
        </div>
    </div>
</div>