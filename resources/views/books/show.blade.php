@extends('layouts.app')
@section('title', $book->title)
@section('page-title', 'Detail Buku')

@section('content')
<div style="display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:start;">

    <!-- MAIN INFO -->
    <div>
        <div class="card">
            <div style="display:flex; gap:24px; align-items:flex-start;">
                <!-- Cover -->
                <div style="width:130px; height:180px; border-radius:10px; background:linear-gradient(135deg,var(--navy) 0%,var(--navy-light) 100%); display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden;">
                    @if($book->cover)
                    <img src="{{ asset('storage/'.$book->cover) }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                    <i class="fas fa-book" style="font-size:40px; color:rgba(255,255,255,0.3);"></i>
                    @endif
                </div>

                <div style="flex:1;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                        <span class="badge badge-blue">{{ $book->category->name }}</span>
                        @if($book->available > 0)
                        <span class="badge badge-green"><i class="fas fa-check-circle" style="margin-right:3px;"></i>Tersedia ({{ $book->available }})</span>
                        @else
                        <span class="badge badge-red"><i class="fas fa-times-circle" style="margin-right:3px;"></i>Tidak Tersedia</span>
                        @endif
                    </div>

                    <h1 style="font-size:22px; font-weight:800; margin-bottom:6px;">{{ $book->title }}</h1>
                    <p style="font-size:14px; color:var(--text-muted); margin-bottom:16px;">oleh <strong>{{ $book->author }}</strong></p>

                    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:16px;">
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">Penerbit</div>
                            <div style="font-size:13px; font-weight:600;">{{ $book->publisher }}</div>
                        </div>
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">Tahun</div>
                            <div style="font-size:13px; font-weight:600;">{{ $book->year }}</div>
                        </div>
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">ISBN</div>
                            <div style="font-size:13px; font-weight:600; font-family:monospace;">{{ $book->isbn }}</div>
                        </div>
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">Total Stok</div>
                            <div style="font-size:13px; font-weight:600;">{{ $book->stock }} eksemplar</div>
                        </div>
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">Lokasi Rak</div>
                            <div style="font-size:13px; font-weight:600;">{{ $book->location ?? '-' }}</div>
                        </div>
                        <div style="padding:10px; background:#f8fafc; border-radius:8px;">
                            <div style="font-size:10px; color:#64748b; text-transform:uppercase; font-weight:600; margin-bottom:3px;">Dipinjam</div>
                            <div style="font-size:13px; font-weight:600;">{{ $book->stock - $book->available }}x</div>
                        </div>
                    </div>

                    @if($book->description)
                    <p style="font-size:13.5px; color:#475569; line-height:1.7;">{{ $book->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <!-- LOAN HISTORY -->
        <div class="card" style="margin-top:20px;">
            <div class="card-header">
                <div class="card-title">Riwayat Peminjaman</div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($book->loans()->latest()->take(10)->get() as $loan)
                        <tr>
                            <td style="font-family:monospace; font-size:12px;">{{ $loan->loan_code }}</td>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                            <td>{{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d M Y') : '-' }}</td>
                            <td>
                                @if($loan->status === 'returned') <span class="badge badge-gray">Dikembalikan</span>
                                @elseif($loan->status === 'active') <span class="badge badge-green">Aktif</span>
                                @elseif($loan->status === 'overdue') <span class="badge badge-red">Terlambat</span>
                                @else <span class="badge badge-blue">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center; padding:16px; color:#64748b; font-size:13px;">Belum pernah dipinjam</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- SIDEBAR ACTIONS -->
    <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">Aksi</div>
            @if($book->available > 0)
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                <a href="{{ route('loans.create', ['book_id' => $book->id]) }}" class="btn btn-accent" style="width:100%; justify-content:center; margin-bottom:10px;">
                    <i class="fas fa-hand-holding"></i> Pinjamkan Buku
                </a>
                @else
                <form action="{{ route('loans.request') }}" method="POST" style="margin-bottom:10px;"
                      onsubmit="return confirm('Ajukan peminjaman buku ini? Menunggu persetujuan petugas.')">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="fas fa-hand-holding"></i> Ajukan Peminjaman
                    </button>
                </form>
                @endif
            @else
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                <div style="font-size: 13px; color: var(--red); font-weight: 600; text-align: center; margin-bottom: 10px;">
                    Buku habis (sedang dipinjam)
                </div>
                @else
                <form action="{{ route('reservations.store') }}" method="POST" style="margin-bottom:10px;">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button type="submit" class="btn btn-outline" style="width:100%; justify-content:center;">
                        <i class="fas fa-clock"></i> Tambah Antrian
                    </button>
                </form>
                @endif
            @endif

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
            <a href="{{ route('books.edit', $book) }}" class="btn btn-outline" style="width:100%; justify-content:center; margin-bottom:10px;">
                <i class="fas fa-edit"></i> Edit Buku
            </a>
            <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width:100%; justify-content:center;">
                    <i class="fas fa-trash"></i> Hapus Buku
                </button>
            </form>
            @endif
        </div>

        <div class="card">
            <div class="card-title" style="margin-bottom:12px;">Statistik</div>
            <div style="display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:#64748b;">Total Pinjam</span>
                    <strong>{{ $book->loans()->count() }}x</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:#64748b;">Sedang Dipinjam</span>
                    <strong>{{ $book->loans()->whereIn('status',['active','overdue'])->count() }}x</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:#64748b;">Reservasi Aktif</span>
                    <strong>{{ $book->reservations()->whereIn('status',['waiting','ready'])->count() }}</strong>
                </div>
            </div>
        </div>

        <a href="{{ route('books.index') }}" class="btn btn-outline" style="justify-content:center;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
