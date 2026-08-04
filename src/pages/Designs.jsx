import { useEffect, useState } from 'react';
import { listDesigns } from '../services/designs';
import { Link } from 'react-router-dom';

export default function Designs() {
  const [designs, setDesigns] = useState([]);
  const [loading, setLoading] = useState(false);

  useEffect(()=>{
    (async ()=>{
      setLoading(true);
      try{
        const res = await listDesigns({ per_page: 20 });
        setDesigns(Array.isArray(res?.data) ? res.data : []);
      }catch(e){
        console.error(e);
      }finally{setLoading(false)}
    })();
  },[]);

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h2 className="text-2xl font-semibold">My Designs</h2>
          <p className="text-sm text-slate-600">Your created designs will appear here.</p>
        </div>
        <div>
          <Link to="/designs/create" className="px-4 py-2 bg-kandura-sand text-white rounded-lg shadow-md">Create Design</Link>
        </div>
      </div>

      {loading ? (
        <div className="text-sm text-slate-500">Loading designs…</div>
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {designs.length === 0 ? (
            <div className="text-sm text-slate-500">No designs yet — create your first design.</div>
          ) : designs.map(d => (
            <article key={d.id} className="bg-white border rounded-xl p-4 shadow-card">
              <div className="h-48 bg-gray-100 rounded-md mb-3 flex items-center justify-center text-sm text-slate-400">{d.main_image_url ? <img src={d.main_image_url} alt="" className="w-full h-full object-cover rounded-md" /> : 'No Image'}</div>
              <h3 className="font-medium">{d.name?.current || d.name?.en || 'Unnamed'}</h3>
              <div className="text-sm text-slate-600">{d.price ? `${d.price} AED` : ''}</div>
            </article>
          ))}
        </div>
      )}
    </div>
  );
}
