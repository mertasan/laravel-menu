# Raw Items

<a name="raw-items"></a>
## Raw Items

To insert items as plain text instead of hyper-links you can use `raw()`:

```php
$menu->raw('Item Title', ['class' => 'some-class']);  

$menu->add('About', 'about');
$menu->About->raw('Another Plain Text Item')

/* Output as an unordered list:
 * <ul>
 *   ...
 *   <li class="some-class">Item's Title</li>
 *   <li>
 *     About
 *     <ul>
 *       <li>Another Plain Text Item</li>
 *     </ul>
 *   </li>
 *   ...
 * </ul>
 */
```
