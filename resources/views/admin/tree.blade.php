<ul id="tree1">

                        @foreach($categories as $category)

                            <li>

                                {{ $category->category_name	 }}

                                @if(count($category->childs))

								<?php
								echo "<pre>";
								print_r($category->childs);
								?>

                                @endif

                            </li>

                        @endforeach

                    </ul>