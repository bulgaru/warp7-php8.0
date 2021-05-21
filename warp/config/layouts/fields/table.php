<table class="uk-table uk-table-middle tm-width">
    <thead>
        <tr>
            <th><?php echo $node->first('rows')->attr('label') ?></th>
            <?php foreach ($node->children('field') as $field) : ?>
            <th><?php echo $field->attr('column') ?: $field->attr('label') ?></th>
            <?php endforeach ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($node->find('rows > row') as $row) : ?>
        <?php $id = $row->text() ?>
        <tr>
            <td><?php echo $id ?></td>
            <?php foreach ($node->children('field') as $field) : ?>
            <td>
                <?php
                    $fname = $field->attr('name');
                    $fvalue = isset($value[$id][$fname]) ? $value[$id][$fname] : $node->attr('default');

                    echo $this['field']->render($field->attr('type'), "{$name}[{$id}][{$fname}]", $fvalue, $field, compact('field'));
                ?>
            </td>
            <?php endforeach ?>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>