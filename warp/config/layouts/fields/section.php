<hr class="uk-article-divider">

<?php

if ($name = $node->attr('name')) {
	printf('<h2>%s</h2>', $name);
}

if ($description = $node->attr('description')) {
	printf('<p>%s</p>', $description);
}
