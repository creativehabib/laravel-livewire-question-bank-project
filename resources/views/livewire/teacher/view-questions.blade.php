<div class="bangla flex lg:gap-5 justify-between">
    <div class="flex-1 max-w-4xl mx-auto">
        <div class="mb-2 sticky top-14 text-sm md:text-md flex gap-2 items-center bg-gray-50 rounded p-2 border justify-between z-10">
            <p class="bg-gray-200 px-2 py-1.5 rounded">
                <span class="hidden md:inline-block">Selected:</span> {{ count($selectedQuestions) }} / {{ $this->availableQuestions->count() }}
            </p>
            <a class="flex gap-1 items-center bg-gray-200 rounded hover:bg-gray-300 px-3 py-1.5" href="#">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 576 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"></path></svg>
                <span>View</span>
            </a>
            <button wire:click="saveSelection" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded">
                <span class="flex items-center gap-1"><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM224 416c-35.346 0-64-28.654-64-64 0-35.346 28.654-64 64-64s64 28.654 64 64c0 35.346-28.654 64-64 64zm96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A11.996 11.996 0 0 1 320 111.48z"></path></svg> Save</span>
            </button>
        </div>
        
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">{{ session('success') }}</div>
        @endif

        <div class="flex gap-2 justify-between my-2">
            <p class="p-2 rounded border line-clamp-1 truncate">টাইটেল - {{ $questionSet->name }}</p>
            <button wire:click="toggleSelectAll" class="shrink-0 border px-2 rounded hover:border-green-500 flex items-center gap-1">
                Select All <span class="ml-1 text-[10px] bg-red-500 text-white px-1.5 py-0.5 rounded-full font-semibold">NEW</span>
            </button>
        </div>
        <div class="text-center bg-yellow-50 p-1 rounded mb-6">প্রশ্নে ভুল পেলে রিপোর্ট করে প্রশ্নব্যাংক সমৃদ্ধ করুন ।</div>

        <div class="relative">
            @forelse ($this->availableQuestions as $question)
                <div wire:click="toggleSelection('{{ $question->id }}')" 
                     class="bg-white rounded-lg border my-2.5 transition-all duration-150 cursor-pointer {{ in_array($question->id, $selectedQuestions) ? 'border-green-500 border-x-8' : 'border-gray-200' }}">
                    <div class="p-2">
                        <div class="flex items-center justify-between mb-1">
                            <div class="mr-1">
                                <input type="checkbox" @if(in_array($question->id, $selectedQuestions)) checked @endif class="hidden">
                            </div>
                            <div class="flex gap-2 items-center text-xs">
                                @foreach($question->tags as $tag)
                                    <span class="bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded-full">{{ $tag->name }}</span>
                                @endforeach
                                <button wire:click.stop="toggleExplanation({{ $question->id }})" class="shrink-0 bg-green-600 hover:bg-green-500 text-white rounded-full px-1.5 py-0.5">ব্যাখা দেখুন</button>
                            </div>
                        </div>
                        <div class="p-1">
                            <div class="flex items-baseline flex-wrap">
                                <span class="mr-1">{{ $loop->iteration }}.</span>
                                <div class="flex-1">{!! $question->title !!}</div>
                            </div>
                            <div class="grid gap-1 grid-cols-2 mt-2">
                                @foreach ($question->options as $option)
                                <div class="p-2 flex items-center gap-1 rounded-lg bg-gray-100">
                                    <div class="flex items-center justify-center h-5 w-5 border rounded-full p-0.5 {{ $option->is_correct ? 'bg-blue-500 text-white border-blue-500' : 'border-gray-400' }}">{{ mb_chr(2453 + $loop->index) }}</div>
                                    <div>{!! $option->option_text !!}</div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-2 text-green-700 rounded transition-all duration-300 {{ $showExplanationFor === $question->id ? 'max-h-screen opacity-100 bg-green-50 p-2' : 'max-h-0 opacity-0' }}">
                                @if($showExplanationFor === $question->id && $question->explanation)
                                    <div>{!! $question->explanation !!}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg border my-2.5 p-6 text-center text-gray-500">
                    আপনার ফিল্টার অনুযায়ী কোনো প্রশ্ন পাওয়া যায়নি।
                </div>
            @endforelse
        </div>
    </div>

    <div>
        <div class="fixed inset-0 min-h-screen z-[998] bg-black/50 hidden"></div>
        <div class="hidden bg-white fixed top-0 right-0 z-[1000] overflow-y-auto lg:z-0 py-5 lg:py-0 lg:sticky lg:block lg:top-16 h-full w-80  print:hidden h-screen overflow-y-scroll sidebar">
          <div class="px-3 lg:px-1 bangla ">
            <div class="border rounded-t text-lg font-bold py-2 text-center flex justify-center items-center gap-2">এডভান্সড ফিল্টার মেনু</div>
            <div class="my-2.5">
              <div class="flex gap-2 mb-2">
                <input type="text" placeholder="কিওয়ার্ড সার্চ করুন" class="bangla rounded-md border-gray-300 flex-1">
                <button class="rounded px-3 bg-gray-200 hover:bg-gray-300 ">সার্চ করুন</button>
              </div>
            </div>
            <div class="my-4 rounded-lg border border-green-400 bg-gradient-to-r from-green-50 to-white p-2">
              <div class="mb-2 flex flex-col items-center justify-center">
                <p class="text-center font-bold mt-3 text-lg text-green-700">ইউনিক প্রশ্ন তৈরী</p>
                <p class="text-center my-2 text-gray-600">পূর্বের তৈরী কোনো একটি প্রশ্নের সকল প্রশ্ন বাদ দিয়ে নতুন প্রশ্ন তৈরী করতে পারবেন।</p>
                <p>একাধিক ব্যাচের জন্য গুরুত্বপূর্ণ</p>
              </div>
              <p class="text-center text-red-500">তৈরীকৃত কোনো প্রশ্ন নেই!</p>
            </div>
            <div class="my-4 rounded-lg border ">
              <div class="font-bold p-2 bg-gray-100 rounded-t-lg">ইপ্রশ্নব্যাংক স্পেশাল সার্চ</div>
              <div class="p-2">
                <label class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                  <input class="mr-2 rounded" id="repeated_board" name="subject" type="checkbox" value="repeated_board">
                  <span class="w-full">রিপিটেড বোর্ড প্রশ্ন </span>
                </label>
                <div class="">
                  <label for="math" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                    <input id="math" class="mr-2 rounded" type="checkbox">
                    <span class="w-full">গাণিতিক</span>
                  </label>
                </div>
                <div class="">
                  <label for="theory" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                    <input id="theory" class="mr-2 rounded" type="checkbox">
                    <span class="w-full">তত্ত্বীয়</span>
                  </label>
                </div>
                <div class="">
                  <label for="picture" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                    <input id="picture" class="mr-2 rounded" type="checkbox">
                    <span class="w-full">চিত্রযুক্ত প্রশ্ন</span>
                  </label>
                </div>
                <div class="">
                  <label for="multi_part" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                    <input id="multi_part" class="mr-2 rounded" type="checkbox">
                    <span class="w-full">বহুপদী সমাপ্তিসূচক</span>
                  </label>
                </div>
                <div class="">
                  <label for="info_based" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                    <input id="info_based" class="mr-2 rounded" type="checkbox">
                    <span class="w-full">অভিন্ন তথ্যভিত্তিক</span>
                  </label>
                </div>
              </div>
            </div>
            <div class="my-2">
              <div class="bg-white border rounded-lg my-4">
                <div class="p-2 bg-gray-100 ">
                  <p class="font-bold">টপিক - <span class="font-normal truncate">অধ্যায় ১ - বাস্তব সংখ্যা</span>
                  </p>
                </div>
                <div class="border-t text-gray-700">
                  <div class="p-2">
                    <div>
                      <label for="6745afbd2415487576bb034a" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745afbd2415487576bb034a" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">বাস্তব সংখ্যার শ্রেণিবিন্যাস</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745afc42415487576bb035e" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745afc42415487576bb035e" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">স্বাভাবিক সংখ্যা</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745afd32415487576bb0368" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745afd32415487576bb0368" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">পূর্ণ সংখ্যা</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745afe22415487576bb0378" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745afe22415487576bb0378" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">ভগ্নাংশ সংখ্যা</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745afee2415487576bb0380" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745afee2415487576bb0380" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">মূলদ সংখ্যা</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745b0112415487576bb0395" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745b0112415487576bb0395" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">অমূলদ সংখ্যা</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745b0482415487576bb040a" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745b0482415487576bb040a" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">বাস্তব সংখ্যার যোগ ও গুণন প্রক্রিয়ার মৌলিক বৈশিষ্ট্য</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745b0562415487576bb041e" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745b0562415487576bb041e" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">দশমিক ভগ্নাংশ</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745b06c2415487576bb042d" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745b06c2415487576bb042d" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">আবৃত্ত দশমিক ভগ্নাংশ</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745b0842415487576bb0448" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745b0842415487576bb0448" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">আবৃত্ত দশমিক ভগ্নাংশকে সাধারণ ভগ্নাংশে পরিবর্তন</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745b0b42415487576bb047f" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745b0b42415487576bb047f" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">সদৃশ আবৃত্ত দশমিক এবং অসদৃশ আবৃত্ত দশমিক ভগ্নাংশ</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745b0fb2415487576bb04d5" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745b0fb2415487576bb04d5" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">আবৃত দশমিক ভগ্নাংশের যোগ ও বিয়োগ</span>
                      </label>
                    </div>
                    <div>
                      <label for="6745b10c2415487576bb04dd" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                        <input id="6745b10c2415487576bb04dd" class="mr-2 rounded" type="checkbox">
                        <span class="w-full">আবৃত দশমিক ভগ্নাংশের যোগ ও বিয়োগ, গুণ ও ভাগ</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-white border rounded-lg my-4">
              <div class="bg-gray-100 p-2 flex justify-between items-center">
                <p class="font-bold">বোর্ড</p>
                <span>
                  <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"></path>
                  </svg>
                </span>
              </div>
              <div class="border-t text-gray-700">
                <div class="p-2">
                  <div class="p-2">
                    <select>
                      <option value="">Year</option>
                      <option value="2024">2024</option>
                      <option value="2023">2023</option>
                      <option value="2022">2022</option>
                      <option value="2021">2021</option>
                      <option value="2020">2020</option>
                      <option value="2019">2019</option>
                      <option value="2018">2018</option>
                      <option value="2017">2017</option>
                      <option value="2016">2016</option>
                      <option value="2015">2015</option>
                    </select>
                  </div>
                  <div class="">
                    <label for="6571f154dcac0f5f301a9b70" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6571f154dcac0f5f301a9b70" class="mr-2 rounded" type="checkbox">
                      <span class="w-full">ঢাকা বোর্ড</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6571f163dcac0f5f301a9b74" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6571f163dcac0f5f301a9b74" class="mr-2 rounded" type="checkbox">
                      <span class="w-full">রাজশাহী বোর্ড</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6571f187dcac0f5f301a9b78" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6571f187dcac0f5f301a9b78" class="mr-2 rounded" type="checkbox">
                      <span class="w-full">ময়মংসিংহ বোর্ড</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6571f193dcac0f5f301a9b7c" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6571f193dcac0f5f301a9b7c" class="mr-2 rounded" type="checkbox">
                      <span class="w-full">দিনাজপুর বোর্ড</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6571f19cdcac0f5f301a9b80" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6571f19cdcac0f5f301a9b80" class="mr-2 rounded" type="checkbox">
                      <span class="w-full">কুমিল্লা বোর্ড</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6571f218e96420f6ee1f913f" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6571f218e96420f6ee1f913f" class="mr-2 rounded" type="checkbox">
                      <span class="w-full">চট্রগ্রাম বোর্ড</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6571f224e96420f6ee1f9143" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6571f224e96420f6ee1f9143" class="mr-2 rounded" type="checkbox">
                      <span class="w-full">সিলেট বোর্ড</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6571f22ee96420f6ee1f9147" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6571f22ee96420f6ee1f9147" class="mr-2 rounded" type="checkbox">
                      <span class="w-full">যশোর বোর্ড</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6571f238e96420f6ee1f914b" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6571f238e96420f6ee1f914b" class="mr-2 rounded" type="checkbox">
                      <span class="w-full">বরিশাল বোর্ড</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-white border rounded-lg my-4">
              <div class="bg-gray-100  p-2 flex justify-between items-center">
                <p class="font-bold"> শীর্ষস্থানীয় স্কুল </p>
                <span>
                  <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"></path>
                  </svg>
                </span>
              </div>
              <div class="border-t text-gray-700">
                <div class="p-2">
                  <div class="p-2">
                    <select>
                      <option value="">Year</option>
                      <option value="2023">2023</option>
                      <option value="2022">2022</option>
                      <option value="2021">2021</option>
                      <option value="2020">2020</option>
                      <option value="2019">2019</option>
                      <option value="2018">2018</option>
                      <option value="2017">2017</option>
                      <option value="2016">2016</option>
                      <option value="2015">2015</option>
                    </select>
                  </div>
                  <div class="">
                    <label for="658d646bdcbc3cf3bb5c63c0" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="658d646bdcbc3cf3bb5c63c0" class="mr-2 rounded" type="checkbox">
                      <span title="আদমজী ক্যান্টনমেন্ট কলেজ, ঢাকা" class="w-full truncate">আদমজী ক্যান্টনমেন্ট কলেজ, ঢাকা</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="658dcc39e9ddb1e55d0af2f7" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="658dcc39e9ddb1e55d0af2f7" class="mr-2 rounded" type="checkbox">
                      <span title="রাজউক উত্তরা মডেল কলেজ, ঢাকা" class="w-full truncate">রাজউক উত্তরা মডেল কলেজ, ঢাকা</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="658dcc8ce9ddb1e55d0af2fb" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="658dcc8ce9ddb1e55d0af2fb" class="mr-2 rounded" type="checkbox">
                      <span title="বরিশাল ক্যাডেট কলেজ" class="w-full truncate">বরিশাল ক্যাডেট কলেজ</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="658dcdbce9ddb1e55d0af307" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="658dcdbce9ddb1e55d0af307" class="mr-2 rounded" type="checkbox">
                      <span title="আইডিয়াল স্কুল এন্ড কলেজ, মতিঝিল, ঢাকা" class="w-full truncate">আইডিয়াল স্কুল এন্ড কলেজ, মতিঝিল, ঢাকা</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="658fe788b86e21f3c99c208d" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="658fe788b86e21f3c99c208d" class="mr-2 rounded" type="checkbox">
                      <span title="ভিকারুননিসা নূন স্কুল এন্ড কলেজ, ঢাকা" class="w-full truncate">ভিকারুননিসা নূন স্কুল এন্ড কলেজ, ঢাকা</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="65958c8dafbe0290abb738b8" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="65958c8dafbe0290abb738b8" class="mr-2 rounded" type="checkbox">
                      <span title="বগুড়া ক্যান্টনমেন্ট পাবলিক স্কুল এন্ড কলেজ" class="w-full truncate">বগুড়া ক্যান্টনমেন্ট পাবলিক স্কুল এন্ড কলেজ</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="65a515fb9840ae8fc1d293d8" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="65a515fb9840ae8fc1d293d8" class="mr-2 rounded" type="checkbox">
                      <span title="মাইলস্টোন কলেজ, ঢাকা" class="w-full truncate">মাইলস্টোন কলেজ, ঢাকা</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="65bfa1d7cc790c1455f74297" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="65bfa1d7cc790c1455f74297" class="mr-2 rounded" type="checkbox">
                      <span title="রাজশাহী ক্যাডেট কলেজ" class="w-full truncate">রাজশাহী ক্যাডেট কলেজ</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6746c6320f8a87b24b7e7cca" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6746c6320f8a87b24b7e7cca" class="mr-2 rounded" type="checkbox">
                      <span title="হলি ক্রস উচ্চ বালিকা বিদ্যালয়, ঢাকা" class="w-full truncate">হলি ক্রস উচ্চ বালিকা বিদ্যালয়, ঢাকা</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6746c6590f8a87b24b7e7d0d" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6746c6590f8a87b24b7e7d0d" class="mr-2 rounded" type="checkbox">
                      <span title="বগুড়া জিলা স্কুল" class="w-full truncate">বগুড়া জিলা স্কুল</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6746c7990f8a87b24b7e7dd4" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6746c7990f8a87b24b7e7dd4" class="mr-2 rounded" type="checkbox">
                      <span title="ডাঃ খাস্তগীর সরকারি বালিকা উচ্চ বিদ্যালয়, চট্টগ্রাম" class="w-full truncate">ডাঃ খাস্তগীর সরকারি বালিকা উচ্চ বিদ্যালয়, চট্টগ্রাম</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="6746d0570f8a87b24b7e82f3" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="6746d0570f8a87b24b7e82f3" class="mr-2 rounded" type="checkbox">
                      <span title="বরিশাল জিলা স্কুল" class="w-full truncate">বরিশাল জিলা স্কুল</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="67c7e49d59b7b892736cedfc" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="67c7e49d59b7b892736cedfc" class="mr-2 rounded" type="checkbox">
                      <span title="ভোলা সরকারি বালিকা উচ্চ বিদ্যালয়" class="w-full truncate">ভোলা সরকারি বালিকা উচ্চ বিদ্যালয়</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="67cbecd701200b09f7e695a1" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="67cbecd701200b09f7e695a1" class="mr-2 rounded" type="checkbox">
                      <span title="আদমজী ক্যান্টনমেন্ট পাবলিক স্কুল, ঢাকা" class="w-full truncate">আদমজী ক্যান্টনমেন্ট পাবলিক স্কুল, ঢাকা</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="67cbecdf01200b09f7e695c2" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="67cbecdf01200b09f7e695c2" class="mr-2 rounded" type="checkbox">
                      <span title="বরগুনা জিলা স্কুল" class="w-full truncate">বরগুনা জিলা স্কুল</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="67d42a1e47de81fabb807e08" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="67d42a1e47de81fabb807e08" class="mr-2 rounded" type="checkbox">
                      <span title="গভর্নমেন্ট ল্যাবরেটরি হাই স্কুল, ঢাকা" class="w-full truncate">গভর্নমেন্ট ল্যাবরেটরি হাই স্কুল, ঢাকা</span>
                    </label>
                  </div>
                  <div class="">
                    <label for="67d42a2d47de81fabb807e18" class="px-2 py-0.5 flex items-center cursor-pointer rounded hover:bg-slate-100">
                      <input id="67d42a2d47de81fabb807e18" class="mr-2 rounded" type="checkbox">
                      <span title="বি এ এফ শাহীন কলেজ, ঢাকা" class="w-full truncate">বি এ এফ শাহীন কলেজ, ঢাকা</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="h-52"></div>
        </div>
      </div>
</div>