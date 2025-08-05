select sm.quantity, products.name,(sm.cost_price * sm.quantity) as total_cost_price, (products.sale_price * sm.quantity) as total_sale_price
from stock_movements sm
join products on products.id = sm.product_id
where sm.type = 'in' 
and sm.movement_date between '2025-08-01' and '2025-08-31'; -- datas de exemplo