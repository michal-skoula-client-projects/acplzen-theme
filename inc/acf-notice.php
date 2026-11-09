<?php

add_action( 'admin_notices', function (): void
{
	if ( class_exists( 'ACF' ) ) {
		return;
	}

	echo '<div class="notice notice-error"><p><strong>ACF Pro není aktivní.</strong> '
		. 'Dokud se znovu nezapne, stránky na webu se nebudou zobrazovat.</p></div>';
} );
