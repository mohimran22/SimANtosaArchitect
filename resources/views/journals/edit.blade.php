@extends('tablar::page')

@section('content')
<div class="page-header d-print-none mb-4">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center">
                <a href="{{ route('journals.index') }}" class="btn btn-dark d-flex align-items-center">
                    <i class="ti ti-arrow-left"></i>
                </a>      
                    <h2 class="page-title mb-0">Edit Jurnal</h2> 
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Terjadi Kesalahan!</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('journals.update', $journal->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            
                            <div class="row mb-3 align-items-center">
                                {{-- @if(auth()->user()->hasRole('Super-Admin'))
                                    <div class="col-md-4 mb-3">
                                        <label for="license_id" class="form-label">Pilih Lisensi</label>
                                        <select name="license_id" id="license_id" class="form-select" disabled>
                                            @foreach ($licenses as $license)
                                                <option value="{{ $license->id }}" 
                                                    {{ $journal->license_id == $license->id ? 'selected' : '' }}>
                                                    {{ $license->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="license_id" value="{{ $journal->license_id }}">
                                    </div>
                                @endif --}}

                                <div class="col-md-4 mb-3">
                                    <label for="journal_code" class="required">No Transaksi</label>
                                    <input type="text" name="journal_code" 
                                        class="form-control" value="{{ old('journal_code', $journal->journal_code) }}" readonly>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="transaction_date">Tanggal Transaksi</label>
                                    <input type="date" name="transaction_date" class="form-control"
                                        value="{{ old('transaction_date', $journal->transaction_date) }}" required>
                                </div>
                            </div>

                            <h4>Detail Akun</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:20%">Akun</th>
                                        <th style="width:20%">Deskripsi</th>
                                        <th style="width:20%">User</th>
                                        <th style="width:10%">Debit</th>
                                        <th style="width:10%">Kredit</th>
                                        <th style="width:5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="detail-rows">
                                    @if(isset($journal) && $journal->details)
                                        @foreach ($journal->details as $i => $detail)
                                            <tr>
                                                {{-- Pilih Akun --}}
                                                <td>
                                                    <select name="details[{{ $i }}][account_id]" 
                                                            class="form-select select2 account-select" 
                                                            data-row="{{ $i }}" required>
                                                        <option value="{{ $detail->account_id }}" 
                                                            data-code="{{ $detail->account->account_code }}"
                                                            data-person-type="{{ $detail->account->person_type }}" 
                                                            selected>
                                                            {{ $detail->account->account_code }} - {{ $detail->account->account_name }}
                                                        </option>
                                                    </select>
                                                </td>

                                                {{-- Deskripsi --}}
                                                <td>
                                                    <input type="text" 
                                                        name="details[{{ $i }}][description]" 
                                                        class="form-control"
                                                        value="{{ old("details.$i.description", $detail->description) }}">
                                                </td>

                                                <td>
                                                    <select name="details[{{ $i }}][person]" 
                                                            class="form-control select2 user-select" 
                                                            data-row="{{ $i }}" 
                                                            data-selected="{{ $detail->person ?? '' }}">
                                                        <option value="">-- Pilih User --</option>
                                                        @php
                                                            if ($detail->person_type === 'employee') {
                                                                $users = $employees;
                                                            } else {
                                                                $users = collect();
                                                            }
                                                        @endphp
                                                        @foreach ($users as $u)
                                                            <option value="{{ $u->id }}" {{ $u->id == $detail->person ? 'selected' : '' }}>
                                                                {{ $u->fullname }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                
                                                <td>
                                                    <input type="number" step="0.01" 
                                                        name="details[{{ $i }}][debit]" 
                                                        class="form-control debit-input" 
                                                        value="{{ old("details.$i.debit", $detail->debit) }}"
                                                        {{ $detail->credit ? 'disabled' : '' }}>

                                                    @if($detail->credit)
                                                        <input type="hidden" name="details[{{ $i }}][debit]" value="{{ $detail->debit }}">
                                                    @endif
                                                </td>

                                                
                                                <td>
                                                    <input type="number" step="0.01" 
                                                        name="details[{{ $i }}][credit]" 
                                                        class="form-control credit-input" 
                                                        value="{{ old("details.$i.credit", $detail->credit) }}"
                                                        {{ $detail->debit ? 'disabled' : '' }}>

                                                    @if($detail->debit)
                                                        <input type="hidden" name="details[{{ $i }}][credit]" value="{{ $detail->credit }}">
                                                    @endif
                                                </td>
                                                <td><button type="button" class="btn btn-sm btn-dark remove-row" title="Hapus">
                                                            <i class="ti ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="6"><button type="button" id="add-row" class="btn btn-sm btn-dark text-white">Tambah Baris</button></td>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Subtotal</th>
                                        <th id="subtotal-debit">{{ $journal->details->sum('debit') }}</th>
                                        <th id="subtotal-credit">{{ $journal->details->sum('credit') }}</th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                            @php
                                $isBalanced = $journal->details->sum('debit') == $journal->details->sum('credit');
                            @endphp

                            <div id="balance-status" 
                                class="mt-2 fw-bold {{ $isBalanced ? 'text-success' : 'text-danger' }}">
                                {{ $isBalanced ? '✅ Seimbang' : '❌ Tidak Seimbang' }}
                            </div>

                            <div class="col-md-6 mb-3">
                                    <label for="description">Keterangan</label>
                                    <textarea name="description" class="form-control">{{ old('description', $journal->description) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="enclosure" class="form-label">Lampiran (PDF / Gambar)</label>
                                <input type="file" name="enclosure" class="form-control">

                                @if($journal->enclosure)
                                    <div class="mt-2">
                                        <p>File saat ini:</p>
                                        @if(\Illuminate\Support\Str::endsWith($journal->enclosure, ['.jpg', '.jpeg', '.png']))
                                            <img src="{{ asset('storage/'.$journal->enclosure) }}" alt="Enclosure" width="200">
                                        @elseif(\Illuminate\Support\Str::endsWith($journal->enclosure, ['.pdf']))
                                            <a href="{{ asset('storage/'.$journal->enclosure) }}" target="_blank">Lihat PDF</a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-dark text-white">Simpan Perubahan</button>
                            </div>
                            
                            {{-- @if(!auth()->user()->hasRole('Super-Admin'))
                                    <input type="hidden" id="activeLicenseId" value="{{ $activeLicenseId }}">
                            @endif --}}
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>   
@endsection


@push('js')

<script>
$(document).ready(function () {

    $('.select2').select2({ placeholder: "-- Pilih --", width: '100%' });

    let accountsData = [];

    // 🔹 Ambil semua akun (single license)
    function loadAccounts() {
        $.get(`/get-accounts`, function (data) {
            accountsData = data;

            $('.account-select').each(function () {
                if (!$(this).val()) {
                    renderAccountOptions($(this));
                }
            });
        });
    }

    function renderAccountOptions($select) {
        if ($select.hasClass("select2-hidden-accessible")) {
            $select.select2("destroy");
        }
        $select.empty().append('<option value="">-- Pilih Akun --</option>');

        $.each(accountsData, function (_, account) {
            $select.append(`
                <option value="${account.id}" 
                        data-code="${account.account_code}" 
                        data-person-type="${account.person_type}">
                    ${account.account_code} - ${account.account_name}
                </option>
            `);
        });

        $select.select2({ placeholder: "-- Pilih Akun --", width: '100%' });
    }

    // 🔹 User (employee only)
    function renderUserOptions($select, personType, selected = null) {
        if ($select.hasClass("select2-hidden-accessible")) {
            $select.select2("destroy");
        }

        $select.empty().append('<option value="">-- Pilih User --</option>');

        if (personType !== 'employee') return;

        $.get('/get-employees', function (data) {
            $.each(data, function (_, user) {
                $select.append(`
                    <option value="${user.id}" ${selected == user.id ? 'selected' : ''}>
                        ${user.name}
                    </option>
                `);
            });
        });

        $select.select2({ placeholder: "-- Pilih User --", width: '100%' });
    }

    const debitOnly = new Set(["1","5","6"]);   
    const creditOnly = new Set(["2","3","4"]);  

    function applyDebitCreditRule($row, accountCode) {
        const firstDigit = accountCode.charAt(0);

        const $debit  = $row.find('.debit-input');
        const $credit = $row.find('.credit-input');

        $debit.prop('disabled', false);
        $credit.prop('disabled', false);

        if (debitOnly.has(firstDigit)) {
            $credit.prop('disabled', true).val('');
        } else if (creditOnly.has(firstDigit)) {
            $debit.prop('disabled', true).val('');
        }
    }

    // 🔹 Change akun
    $(document).on('change', '.account-select', function () {
        const $row = $(this).closest('tr');

        const accountCode = String($(this).find(':selected').data('code') || '');
        const personType  = $(this).find(':selected').data('person-type');

        // user
        renderUserOptions($row.find('.user-select'), personType);

        // debit/credit rule
        applyDebitCreditRule($row, accountCode);
    });

    // 🔹 INIT EDIT MODE (penting!)
    $('.account-select').each(function () {
        const $row = $(this).closest('tr');

        const accountCode = String($(this).find(':selected').data('code') || '');
        const personType  = $(this).find(':selected').data('person-type');
        const $userSelect = $row.find('.user-select');
        const selectedUser = $userSelect.data('selected');

        // load user lama
        renderUserOptions($userSelect, personType, selectedUser);

        // apply rule debit/credit
        applyDebitCreditRule($row, accountCode);
    });

    // 🔹 Tambah row
    $('#add-row').click(function () {
        const rowCount = $('#detail-rows tr').length;

        const newRow = `
            <tr>
                <td>
                    <select name="details[${rowCount}][account_id]" 
                            class="form-select account-select" required></select>
                </td>
                <td><input type="text" name="details[${rowCount}][description]" class="form-control"></td>
                <td>
                    <select name="details[${rowCount}][person]" 
                            class="form-select user-select"></select>
                </td>
                <td><input type="number" step="0.01" name="details[${rowCount}][debit]" class="form-control debit-input"></td>
                <td><input type="number" step="0.01" name="details[${rowCount}][credit]" class="form-control credit-input"></td>
                <td>
                    <button type="button" class="btn btn-sm btn-dark remove-row">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#detail-rows').append(newRow);

        renderAccountOptions($('#detail-rows tr:last .account-select'));
    });

    // 🔹 Hapus row
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        calculateSubtotals();
    });

    // 🔹 Hitung subtotal
    function calculateSubtotals() {
        let totalDebit = 0, totalCredit = 0;

        $('#detail-rows tr').each(function() {
            totalDebit  += parseFloat($(this).find('.debit-input').val()) || 0;
            totalCredit += parseFloat($(this).find('.credit-input').val()) || 0;
        });

        $('#subtotal-debit').text(totalDebit.toLocaleString('id-ID'));
        $('#subtotal-credit').text(totalCredit.toLocaleString('id-ID'));

        if (totalDebit === totalCredit && totalDebit > 0) {
            $('#balance-status').text('✅ Seimbang').css('color', 'green');
        } else {
            $('#balance-status').text('❌ Tidak Seimbang').css('color', 'red');
        }
    }

    // 🔹 Input debit/kredit
    $(document).on('input', '.debit-input, .credit-input', function() {
        let val = parseFloat($(this).val()) || 0;
        $(this).val(Math.abs(val));

        if ($(this).hasClass('debit-input')) {
            $(this).closest('tr').find('.credit-input').val('');
        } else {
            $(this).closest('tr').find('.debit-input').val('');
        }

        calculateSubtotals();
    });

    // 🔹 Validasi submit
    $('form').on('submit', function (e) {
        let totalDebit = 0, totalCredit = 0;

        $('#detail-rows tr').each(function() {
            totalDebit  += parseFloat($(this).find('.debit-input').val()) || 0;
            totalCredit += parseFloat($(this).find('.credit-input').val()) || 0;
        });

        if (totalDebit !== totalCredit) {
            e.preventDefault();
            alert('Transaksi tidak balance!');
        }
    });

    loadAccounts();
});
</script>

@endpush