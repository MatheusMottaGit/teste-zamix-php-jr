select p.name, r.description, u.name as employee, ri.items_quantity, r.request_date
from requests r
join users u on u.id = r.user_id
join request_items ri on ri.request_id = r.id
join products p on p.id = ri.product_id;