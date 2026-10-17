<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('nim_nidn', 'like', "%$s%");
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $members = $query->latest()->paginate(20)->withQueryString();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'nim_nidn'      => 'nullable|string|max:50',
            'phone'         => 'nullable|string|max:20',
            'program_studi' => 'nullable|string|max:100',
            'role'          => 'required|in:mahasiswa,dosen,staff,admin',
            'password'      => 'required|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('members.index')->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function show(User $member)
    {
        return view('members.show', compact('member'));
    }

    public function edit(User $member)
    {
        return view('members.form', compact('member'));
    }

    public function update(Request $request, User $member)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $member->id,
            'nim_nidn'      => 'nullable|string|max:50',
            'phone'         => 'nullable|string|max:20',
            'program_studi' => 'nullable|string|max:100',
            'role'          => 'required|in:mahasiswa,dosen,staff,admin',
            'password'      => 'nullable|string|min:6',
        ]);

        // Admin tidak boleh menurunkan role akunnya sendiri (supaya tidak terkunci)
        if ((int) $member->id === (int) auth()->id() && $data['role'] !== $member->role) {
            return back()->withInput()->with('error', 'Anda tidak bisa mengubah role akun Anda sendiri.');
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $member->update($data);
        return redirect()->route('members.index')->with('success', 'Data anggota diperbarui!');
    }

    public function destroy(User $member)
    {
        if ((int) $member->id === (int) auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        if ($member->loans()->whereIn('status', ['active', 'overdue'])->exists()) {
            return back()->with('error', 'Anggota masih memiliki buku yang belum dikembalikan!');
        }

        // Pending dibatalkan; stok tidak terpengaruh karena belum dikurangi
        $member->delete();
        return back()->with('success', 'Anggota dihapus!');
    }
}
