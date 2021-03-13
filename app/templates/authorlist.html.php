<h2>User list</h2>
<table>
    <thead>
        <th>Name</th>
        <th>Email</th>
        <th>Edit</th>
    </thead>
    <tbody>
        <?php foreach ($authors as $author) : ?>
            <tr>
                <td><?= $author->name; ?></td>
                <td><?= $author->getEmail()[0]->email; ?></td>
                <td> <a href="<?= $author->id; ?>">Edit permissions</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>