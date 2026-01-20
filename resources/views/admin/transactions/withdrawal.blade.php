@extends('admin.layouts.app')
@section('content')


 <div class="px-4 pt-5">


<ul class="flex text-sm font-medium text-center text-gray-500 rounded-lg shadow sm:flex dark:divide-gray-700 dark:text-gray-400">
    <li class="w-full focus-within:z-10">
        <a href="{{ route('admin.transactions.deposits') }}"  class="inline-block w-full p-4 bg-white border-r border-gray-200 dark:border-gray-700 hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" >Deposits</a>
    </li>
    <li class="w-full focus-within:z-10">
        <a href="{{ route('admin.transactions.withdrawals') }}"  class="inline-block w-full p-4 text-gray-900 bg-gray-100 border-r border-gray-200 dark:border-gray-700 rounded-s-lg focus:ring-4 focus:ring-blue-300  focus:outline-none dark:bg-gray-700 dark:text-white" aria-current="page">Withdrawal</a>
    </li>
</ul>



    <div class="p-4 mt-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
      <!-- Card header -->
      <div class="items-center justify-between lg:flex">
        <div class="mb-4 lg:mb-0">
          <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Deposit Transactions</h3>

        </div>
      </div>
      <!-- Table -->
      <div class="flex flex-col mt-6">
        <div class="overflow-x-auto rounded-lg">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow sm:rounded-lg">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-700">
                  <tr>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      #
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      User
                    </th>
                      <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Date
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Amount
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Payment method
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Status
                    </th>
                      <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                @foreach($withdrawal as $index => $item)
                  <tr>
                    <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap dark:text-white">
                      <span class="font-semibold">#{{ $index+1 }}</span>
                    </td>
                    <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap">
                        <img class="w-10 h-10 rounded-full" style="border-radius: 50%"  height="50" width="50" src="{{ $item->user->avatar_url }}" alt="Neil Sims avatar">
                        <div class="text-sm font-normal text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            <div class="text-base font-semibold text-gray-900 dark:text-white">{{ $item->user->fullname() ?? '' }}</div>
                        </div>
                    </td>
                      <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                        {{ date('d M, Y', strtotime($item->created_at ?? '')) }}
                    </td>
                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                        ${{ number_format($item->amount, 2) ?? '' }}
                    </td>

                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                      <span>{{ $item->payment_method }}</span>
                    </td>
                    <td class="p-4 whitespace-nowrap">
                        {!! $item->adminStatus() !!}

                    </td>
                     <td class="p-4 space-x-2 whitespace-nowrap">
                         <button data-modal-target="default-modal-{{ $item->id }}" data-modal-toggle="default-modal-{{ $item->id }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                             <x-gmdi-remove-red-eye-o class="w-4 h-4 mr-2"/>
                         </button>
                         <button data-modal-toggle="delete-user-modal" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900">
                             <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                         </button>

                        </td>
                  </tr>
                    <!-- Delete User Modal -->
                    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden overflow-x-hidden overflow-y-auto top-4 md:inset-0 h-modal sm:h-full" id="delete-user-modal">
                    <div class="relative w-full h-full max-w-md px-4 md:h-auto">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                            <!-- Modal header -->
                            <div class="flex justify-end p-2">
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white" data-modal-toggle="delete-user-modal">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-6 pt-0 text-center">
                                <svg class="w-16 h-16 mx-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h3 class="mt-5 mb-6 text-lg text-gray-500 dark:text-gray-400">Are you sure you want to delete this item?</h3>
                                <a href="#" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-800">
                                    Yes, I'm sure
                                </a>
                                <a href="#" class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" data-modal-toggle="delete-user-modal">
                                    No, cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                    <!-- Main modal -->
                    <div id="default-modal-{{ $item->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Withdrawal Details
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal-{{ $item->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-4 md:p-5 space-y-4">
                                    <ul style="color: white" class="divide-y rounded-lg">
                                    <li class="flex justify-between items-center py-4 px-6">
                                        <strong class="text-white-700">Transaction ID:</strong>
                                        <span>{{ $item->id."#" }}</span>
                                    </li>
                                    <li class="flex justify-between items-center py-4 px-6">
                                        <strong class="text-white-700">Amount:</strong>
                                        <span>${{ number_format($item->amount, 2) ?? '' }}</span>
                                    </li>
                                    <li class="flex justify-between items-center py-4 px-6">
                                        <strong class="text-white-700">From Account:</strong>
                                        <span>{{ ucfirst(str_replace('_', ' ', $item->from_account ?? 'balance')) }}</span>
                                    </li>
                                    <li class="flex justify-between items-center py-4 px-6">
                                        <strong class="text-white-700">Payment Method:</strong>
                                        <span>{{ $item->payment_method ?? '' }}</span>
                                    </li>
                                    <li class="flex justify-between items-center py-4 px-6">
                                        <strong class="text-white-700">Payment Details:</strong>
                                     @if($item->payment_method == 'crypto')
                                          <div>
                                              <h4>Wallet: {{ $item->wallet ?? '' }}</h4>
                                              <h4>Address: {{ $item->address ?? '' }}</h4>
                                          </div>
                                      @elseif($item->payment_method == 'bank')
                                        @php
                                            $bank = json_decode($item->bank, true)
                                        @endphp
                                        <br><br>
                                          <div>
                                              <h4>Bank: {{ $bank['bank_name'] ?? '' }}</h4>
                                              <h4>Acct Name: {{ $bank['acct_name'] ?? '' }}</h4>
                                              <h4>Acct Num: {{ $bank['acct_number'] ?? '' }}</h4>
                                          </div>
                                      @else
                                          <span>{{ $item->paypal ?? '' }}</span>
                                      @endif
                                    </li>
                                </ul>

                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                    <form method="POST" action="{{ route('admin.transactions.withdrawals.approve', $item->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" onclick="return confirm('Are you sure you want to approve this withdrawal?')">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.transactions.withdrawals.reject', $item->id) }}" class="inline ml-3">
                                        @csrf
                                        <button type="submit" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" onclick="return confirm('Are you sure you want to reject this withdrawal?')">
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
               @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Card Footer -->
      <div class="flex items-center justify-between pt-3 sm:pt-6">
        <div>

      </div>
    </div>
</div>




@endsection
