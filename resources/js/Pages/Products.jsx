import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { api } from '../lib/api';

export default function Products() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function loadData() {
      try {
        // Public route: no CSRF needed
        const productData = await api('/products');
        setProducts(productData);

        // Ping route is still protected
        /* try {
          const ping = await api('/ping');
          console.log('Ping response:', ping);
        } catch (err) {
          console.warn('Ping failed (not logged in):', err.message);
        } */
      } catch (err) {
        console.error('API error:', err.message);
      } finally {
        setLoading(false);
      }
    }

    loadData();
  }, []);

  const addToCart = async (productId) => {
    try {
      // Cart routes are protected → CSRF + credentials required
      let resp = await api('/cart/items', {
        method: 'POST',
        body: JSON.stringify({ product_id: productId, quantity: 1 }),
      });
      alert('Added to cart');
    } catch (err) {
      console.error('Add to cart failed:', err.message);
    }
  };

  return (
    <AuthenticatedLayout
      header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Products</h2>}
    >
      <Head title="Products" />
      <div className="py-6">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="flex justify-end mb-4">
            <Link href="/cart" className="px-3 py-2 bg-gray-800 text-white rounded">
              View Cart
            </Link>
          </div>
          {loading ? (
            <div className="p-6">Loading...</div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              {products.map((p) => (
                <div key={p.id} className="border rounded p-4">
                  <div className="font-medium">{p.name}</div>
                  <div className="text-gray-600">
                    Price: ${Number(p.price).toFixed(2)}
                  </div>
                  <div className="text-gray-600">Stock: {p.stock_quantity}</div>
                  <button
                    className="mt-3 px-3 py-2 bg-blue-600 text-white rounded"
                    onClick={() => addToCart(p.id)}
                  >
                    Add to cart
                  </button>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
