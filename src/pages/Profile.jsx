import { useState, useEffect } from 'react';
import { getProfile } from '../services/profile';

export default function Profile() {
  const [profile, setProfile] = useState(null);

  useEffect(() => {
    (async () => {
      try {
        const data = await getProfile();
        setProfile(data);
      } catch (e) {
        console.error(e);
      }
    })();
  }, []);

  return (
    <div>
      <h2 className="text-2xl font-semibold mb-3">Profile</h2>
      {profile ? (
        <div className="space-y-2 text-sm text-slate-700">
          <div><strong>Name:</strong> {profile.name}</div>
          <div><strong>Phone:</strong> {profile.phone}</div>
        </div>
      ) : (
        <p className="text-sm text-slate-500">Loading profile…</p>
      )}
    </div>
  );
}
