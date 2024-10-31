<?php
/**
 * Plugin Name: Quote Status Copy & Share By Adittaw
 * Description: Adds Copy and Share buttons to blockquotes with automatic post URL inclusion.
 * Version:     1.0
 * Author:      Adittaw
 * Author URI:  https://adittaw.com
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: quote-status-copy-share-by-adittaw
 */



if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Enqueue JavaScript and CSS for the plugin
function qcs_enqueue_scripts() {
    // Register a dummy script to hook the inline script into
    wp_register_script('qcs-script', '', [], '1.0', true);  

    // Enqueue the script to make sure it gets loaded
    wp_enqueue_script('qcs-script');

    // Enqueue the CSS inline after a dummy style handle
    wp_register_style('qcs-style', false, [], '1.0');  
    wp_enqueue_style('qcs-style');

    // Add inline JavaScript for Copy and Share buttons
    wp_add_inline_script('qcs-script', '
        document.addEventListener("DOMContentLoaded", function() {
            const blockquotes = document.querySelectorAll("blockquote");
            const postUrl = window.location.href; // Get the current post/page URL

            blockquotes.forEach(blockquote => {
                // Create "Copy" button
                const copyButton = document.createElement("button");
                copyButton.innerText = "Copy";
                copyButton.classList.add("copy-btn");

                // Create "Share" button
                const shareButton = document.createElement("button");
                shareButton.innerText = "Share";
                shareButton.classList.add("share-btn");

                // Add buttons after blockquote
                blockquote.insertAdjacentElement("afterend", copyButton);
                copyButton.insertAdjacentElement("afterend", shareButton);

                // Copy functionality
                copyButton.addEventListener("click", () => {
                    const quoteText = blockquote.innerText + "\\n\\nRead more: " + postUrl;
                    navigator.clipboard.writeText(quoteText)
                        .then(() => {
                            alert("Quote and post URL copied to clipboard!");
                        });
                });

                // Share functionality (using Web Share API)
                shareButton.addEventListener("click", () => {
                    const quoteText = blockquote.innerText + "\\n\\nRead more: " + postUrl;
                    if (navigator.share) {
                        navigator.share({
                            text: quoteText,
                            url: postUrl
                        }).then(() => {
                            console.log("Thanks for sharing!");
                        }).catch(console.error);
                    } else {
                        alert("Web Share API is not supported in your browser.");
                    }
                });
            });
        });
    ');

    // Add inline CSS for Copy and Share buttons
    wp_add_inline_style('qcs-style', '
        .copy-btn, .share-btn {
            margin: 10px 5px;
            padding: 8px 12px;
            background-color: #0073aa;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .copy-btn:hover, .share-btn:hover {
            background-color: #005177;
        }
    ');
}
add_action('wp_enqueue_scripts', 'qcs_enqueue_scripts');

