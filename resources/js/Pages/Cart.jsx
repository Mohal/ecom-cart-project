import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { api } from '../lib/api';

export default function Cart() {
  const [cart, setCart] = useState(null);
  const [loading, setLoading] = useState(true);

  const loadCart = () => {
    api('/cart').then(setCart).finally(() => setLoading(false));
  };

  useEffect(() => {
    loadCart();
  }, []);

  const updateQuantity = async (productId, quantity) => {
    await api(`/cart/items/${productId}`, {
      method: 'PATCH',
      body: JSON.stringify({ quantity }),
    });
    loadCart();
  };

  const removeItem = async (productId) => {
    await api(`/cart/items/${productId}`, { method: 'DELETE' });
    loadCart();
  };

  const checkout = async () => {
    try {
      const res = await api('/checkout', { method: 'POST' });
      alert(`Order placed! Order #${res.order.id}, Total: $${Number(res.order.total).toFixed(2)}`);
      loadCart();
    } catch (e) {
      let errorMessage = "Checkout failed";

      if (typeof(e.message) === "string") {
        try {
          const parsed = JSON.parse(e.message);

          if (parsed && typeof(parsed) === "object" && parsed.message) {
            errorMessage = parsed.message;
          } else {
            errorMessage = e.message;
          }
        } catch {
          // Not JSON
          errorMessage = e.message;
        }
      }

      alert(errorMessage);
    }
  };

  const subtotal = cart?.items?.reduce((sum, i) => sum + i.quantity * Number(i.unit_price), 0) || 0;

  return (
    <AuthenticatedLayout
      header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Your Cart</h2>}
    >
      <Head title="Cart" />
      <div className="py-6">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="flex justify-between mb-4">
            <Link href="/products" className="px-3 py-2 bg-gray-800 text-white rounded">Back to Products</Link>
            <div className="text-lg">Subtotal: ${subtotal.toFixed(2)}</div>
          </div>

          {loading ? (
            <div className="p-6">Loading...</div>
          ) : (
            <div className="bg-white shadow sm:rounded-lg p-6">
              {cart?.items?.length ? (
                <div className="space-y-4">
                  {cart.items.map(item => (
                    <div key={item.id} className="flex items-center justify-between border-b pb-3">
                      <div>
                        <div className="font-medium">{item.product.name}</div>
                        <div className="text-gray-600">Unit: ${Number(item.unit_price).toFixed(2)}</div>
                      </div>
                      <div className="flex items-center gap-2">
                        <input
                          type="number"
                          min="0"
                          value={item.quantity}
                          onChange={(e) => updateQuantity(item.product_id, parseInt(e.target.value || 0))}
                          className="w-20 border rounded px-2 py-1"
                        />
                        <button
                          className="px-3 py-2 bg-red-600 text-white rounded"
                          onClick={() => removeItem(item.product_id)}
                        >
                          Remove
                        </button>
                      </div>
                    </div>
                  ))}
                  <div className="flex justify-end">
                    <button
                      className="px-4 py-2 bg-green-600 text-white rounded"
                      onClick={checkout}
                    >
                      Checkout
                    </button>
                  </div>
                </div>
              ) : (
                <div>Your cart is empty.</div>
              )}
            </div>
          )}
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
