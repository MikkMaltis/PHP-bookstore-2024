SELECT * FROM d118821_books;

SELECT
	YEAR(O.order_date) AS 'Aasta',
    COUNT(*) AS 'Tellimuste arv',
    ROUND(SUM(b.price), 2) AS 'Müükide summa'
FROM 
	orders o
		LEFT JOIN
	books b ON o.book_id = b.id
GROUP BY YEAR(o.order_date);
