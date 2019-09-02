@extends('artists.layouts.app')
@section('content')
                
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-white">
            <div class="panel-body">
                <div class="cd-pricing-container">
                    <div class="cd-pricing-switcher">
                        <p class="fieldset">
                            <input type="radio" class="no-uniform" name="duration-1" value="monthly" id="monthly-1" checked>
                            <label for="monthly-1">Monthly</label>
                            <input type="radio" class="no-uniform" name="duration-1" value="yearly" id="yearly-1">
                            <label for="yearly-1">Yearly</label>
                            <span class="cd-switch"></span>
                        </p>
                    </div>
                    <ul class="cd-pricing-list cd-bounce-invert">
                        <li>
                            <ul class="cd-pricing-wrapper">
                                <li data-type="monthly" class="is-visible">
                                    <header class="cd-pricing-header">
                                        <h2>Basic</h2>
                                        <div class="cd-price">
                                            <span class="cd-currency">$</span>
                                            <span class="cd-value">30</span>
                                            <span class="cd-duration">mo</span>
                                        </div>
                                    </header>
                                    <div class="cd-pricing-body">
                                        <ul class="cd-pricing-features">
                                            <li><em>256MB</em> Memory</li>
                                            <li><em>1</em> User</li>
                                            <li><em>1</em> Website</li>
                                            <li><em>1</em> Domain</li>
                                            <li><em>Unlimited</em> Bandwidth</li>
                                            <li><em>24/7</em> Support</li>
                                        </ul>
                                    </div>
                                    <footer class="cd-pricing-footer">
                                        <a class="cd-select" href="#">Select</a>
                                    </footer>
                                </li>
                                <li data-type="yearly" class="is-hidden">
                                    <header class="cd-pricing-header">
                                        <h2>Basic</h2>
                                        <div class="cd-price">
                                            <span class="cd-currency">$</span>
                                            <span class="cd-value">320</span>
                                            <span class="cd-duration">yr</span>
                                        </div>
                                    </header>
                                    <div class="cd-pricing-body">
                                        <ul class="cd-pricing-features">
                                            <li><em>256MB</em> Memory</li>
                                            <li><em>1</em> User</li>
                                            <li><em>1</em> Website</li>
                                            <li><em>1</em> Domain</li>
                                            <li><em>Unlimited</em> Bandwidth</li>
                                            <li><em>24/7</em> Support</li>
                                        </ul>
                                    </div>
                                    <footer class="cd-pricing-footer">
                                        <a class="cd-select" href="#">Select</a>
                                    </footer>
                                </li>
                            </ul>
                        </li>
                        <li class="cd-popular">
                            <ul class="cd-pricing-wrapper">
                                <li data-type="monthly" class="is-visible">
                                    <header class="cd-pricing-header">
                                        <h2>Popular</h2>
                                        <div class="cd-price">
                                            <span class="cd-currency">$</span>
                                            <span class="cd-value">60</span>
                                            <span class="cd-duration">mo</span>
                                        </div>
                                    </header>
                                    <div class="cd-pricing-body">
                                        <ul class="cd-pricing-features">
                                            <li><em>512MB</em> Memory</li>
                                            <li><em>3</em> Users</li>
                                            <li><em>5</em> Websites</li>
                                            <li><em>7</em> Domains</li>
                                            <li><em>Unlimited</em> Bandwidth</li>
                                            <li><em>24/7</em> Support</li>
                                        </ul>
                                    </div>
                                    <footer class="cd-pricing-footer">
                                        <a class="cd-select" href="#">Select</a>
                                    </footer>
                                </li>
                                <li data-type="yearly" class="is-hidden">
                                    <header class="cd-pricing-header">
                                        <h2>Popular</h2>
                                        <div class="cd-price">
                                            <span class="cd-currency">$</span>
                                            <span class="cd-value">630</span>
                                            <span class="cd-duration">yr</span>
                                        </div>
                                    </header>
                                    <div class="cd-pricing-body">
                                        <ul class="cd-pricing-features">
                                            <li><em>512MB</em> Memory</li>
                                            <li><em>3</em> Users</li>
                                            <li><em>5</em> Websites</li>
                                            <li><em>7</em> Domains</li>
                                            <li><em>Unlimited</em> Bandwidth</li>
                                            <li><em>24/7</em> Support</li>
                                        </ul>
                                    </div>
                                    <footer class="cd-pricing-footer">
                                        <a class="cd-select" href="#">Select</a>
                                    </footer>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="cd-pricing-wrapper">
                                <li data-type="monthly" class="is-visible">
                                    <header class="cd-pricing-header">
                                        <h2>Premier</h2>
                                        <div class="cd-price">
                                            <span class="cd-currency">$</span>
                                            <span class="cd-value">90</span>
                                            <span class="cd-duration">mo</span>
                                        </div>
                                    </header>
                                    <div class="cd-pricing-body">
                                        <ul class="cd-pricing-features">
                                            <li><em>1024MB</em> Memory</li>
                                            <li><em>5</em> Users</li>
                                            <li><em>10</em> Websites</li>
                                            <li><em>10</em> Domains</li>
                                            <li><em>Unlimited</em> Bandwidth</li>
                                            <li><em>24/7</em> Support</li>
                                        </ul>
                                    </div>
                                    <footer class="cd-pricing-footer">
                                        <a class="cd-select" href="#">Select</a>
                                    </footer>
                                </li>
                                <li data-type="yearly" class="is-hidden">
                                    <header class="cd-pricing-header">
                                        <h2>Premier</h2>
                                        <div class="cd-price">
                                            <span class="cd-currency">$</span>
                                            <span class="cd-value">950</span>
                                            <span class="cd-duration">yr</span>
                                        </div>
                                    </header>
                                    <div class="cd-pricing-body">
                                        <ul class="cd-pricing-features">
                                            <li><em>1024MB</em> Memory</li>
                                            <li><em>5</em> Users</li>
                                            <li><em>10</em> Websites</li>
                                            <li><em>10</em> Domains</li>
                                            <li><em>Unlimited</em> Bandwidth</li>
                                            <li><em>24/7</em> Support</li>
                                        </ul>
                                    </div>
                                    <footer class="cd-pricing-footer">
                                        <a class="cd-select" href="#">Select</a>
                                    </footer>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

@stop