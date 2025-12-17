<?php
$book_title = "Felosofi Teras";
$author_name = "Henry Manampiring";
$quote = "Hidup tenang ketika kita fokus pada hal yang bisa kita kendalikan.";
$quote_author = "Marcus Aurelius";

$description = "Buku yang memperkenalkan ajaran Stoisisme sebuah filsafat hidup dari Yunani Kuno—yang membantu seseorang menghadapi emosi negatif, kecemasan, dan tekanan sehari-hari. 
Buku ini menjelaskan konsep inti Stoik, terutama Dichotomy of Control, yaitu membedakan mana hal yang bisa kita kendalikan dan mana yang tidak.";

?>
<link rel="stylesheet" href="assets/style.css">

<div class="hero-container">

    <div class="hero-content-left">
        <h1 class="book-main-title"><?= $book_title ?></h1>
        <p class="book-description"><?= $description ?></p>
    </div>

    <div class="book-display">
        <div class="book-cover"></div>
    </div>

</div>

<section class="quote-section py-5 text-center">
    <div class="container py-5">
        <h2 class="quote-title">Quote Of The Day</h2>

        <div class="quote-text-wrapper">
            <p class="quote-text">
                "<?= $quote ?>"
            </p>
            <p class="quote-author-name">
                <?= $quote_author ?>
            </p>
        </div>
    </div>
</section>

</section>
