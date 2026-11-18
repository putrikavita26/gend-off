create table if not exists public.makanan_custom_user (
  id uuid primary key default gen_random_uuid(),
  user_id uuid not null references auth.users(id) on delete cascade,
  nama_makanan text not null check (char_length(btrim(nama_makanan)) between 1 and 100),
  kalori_per_100g numeric(8,2) not null check (kalori_per_100g between 0 and 1000),
  protein_per_100g numeric(6,2) not null check (protein_per_100g between 0 and 100),
  karbo_per_100g numeric(6,2) not null check (karbo_per_100g between 0 and 100),
  lemak_per_100g numeric(6,2) not null check (lemak_per_100g between 0 and 100),
  serat_per_100g numeric(6,2) check (serat_per_100g between 0 and 100),
  sumber_data text check (sumber_data is null or char_length(sumber_data) <= 200),
  created_at timestamptz not null default now()
);

create table if not exists public.catatan_makan (
  id uuid primary key default gen_random_uuid(),
  user_id uuid not null references auth.users(id) on delete cascade,
  tanggal date not null default current_date,
  jenis_waktu text not null check (jenis_waktu in ('sarapan', 'makan_siang', 'makan_malam', 'snack')),
  nama_makanan text not null check (char_length(btrim(nama_makanan)) between 1 and 150),
  gram numeric(8,2) not null check (gram between 1 and 3000),
  kalori_kcal numeric(10,2) not null check (kalori_kcal >= 0),
  protein_g numeric(10,2) not null check (protein_g >= 0),
  karbo_g numeric(10,2) not null check (karbo_g >= 0),
  lemak_g numeric(10,2) not null check (lemak_g >= 0),
  serat_g numeric(10,2) check (serat_g is null or serat_g >= 0),
  sumber text not null check (sumber in ('katalog', 'custom')),
  metode_pengolahan text,
  id_makanan_katalog text,
  makanan_custom_id uuid references public.makanan_custom_user(id) on delete set null,
  created_at timestamptz not null default now(),
  check (
    (sumber = 'katalog' and id_makanan_katalog is not null and makanan_custom_id is null)
    or (sumber = 'custom' and id_makanan_katalog is null)
  )
);

create index if not exists makanan_custom_user_owner_name_idx
  on public.makanan_custom_user (user_id, nama_makanan);
create index if not exists catatan_makan_owner_date_idx
  on public.catatan_makan (user_id, tanggal, created_at);

alter table public.makanan_custom_user enable row level security;
alter table public.catatan_makan enable row level security;

drop policy if exists makanan_custom_user_select_own on public.makanan_custom_user;
create policy makanan_custom_user_select_own on public.makanan_custom_user
  for select to authenticated using ((select auth.uid()) = user_id);
drop policy if exists makanan_custom_user_insert_own on public.makanan_custom_user;
create policy makanan_custom_user_insert_own on public.makanan_custom_user
  for insert to authenticated with check ((select auth.uid()) = user_id);
drop policy if exists makanan_custom_user_update_own on public.makanan_custom_user;
create policy makanan_custom_user_update_own on public.makanan_custom_user
  for update to authenticated using ((select auth.uid()) = user_id)
  with check ((select auth.uid()) = user_id);
drop policy if exists makanan_custom_user_delete_own on public.makanan_custom_user;
create policy makanan_custom_user_delete_own on public.makanan_custom_user
  for delete to authenticated using ((select auth.uid()) = user_id);

drop policy if exists catatan_makan_select_own on public.catatan_makan;
create policy catatan_makan_select_own on public.catatan_makan
  for select to authenticated using ((select auth.uid()) = user_id);
drop policy if exists catatan_makan_insert_own on public.catatan_makan;
create policy catatan_makan_insert_own on public.catatan_makan
  for insert to authenticated with check ((select auth.uid()) = user_id);
drop policy if exists catatan_makan_update_own on public.catatan_makan;
create policy catatan_makan_update_own on public.catatan_makan
  for update to authenticated using ((select auth.uid()) = user_id)
  with check ((select auth.uid()) = user_id);
drop policy if exists catatan_makan_delete_own on public.catatan_makan;
create policy catatan_makan_delete_own on public.catatan_makan
  for delete to authenticated using ((select auth.uid()) = user_id);

grant select, insert, update, delete on public.makanan_custom_user to authenticated;
grant select, insert, update, delete on public.catatan_makan to authenticated;

notify pgrst, 'reload schema';
