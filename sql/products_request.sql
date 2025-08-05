select r.description, r.request_date, u.name, ri.items_quantity, p.name
from requests r
join users u on u.id = r.user_id
join request_items ri on ri.request_id = r.id
join products p on p.id = ri.product_id;