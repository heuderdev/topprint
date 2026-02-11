<div class="relative w-full max-w-md mx-auto">
    <!-- Card com shadow profunda -->
    <div
        class="bg-white/80 dark:bg-zinc-800/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/50 dark:border-zinc-700/50 overflow-hidden transform hover:scale-[1.02] transition-all duration-300">
        <div class="p-8 space-y-8">
            <!-- Header estiloso -->
            <div class="text-center space-y-3">
                <div
                    class="w-20 h-20 mx-auto bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <flux:icon name="lock-closed" class="w-10 h-10 text-white" />
                </div>
                <div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-zinc-900 to-zinc-700 dark:from-white dark:to-zinc-200 bg-clip-text text-transparent">
                        Faça Login</h1>
                    <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-1">Acesse sua conta com segurança</p>
                </div>
            </div>

            <!-- Form com inputs glassmorphism -->
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Email</label>
                    <flux:input wire:model.live="email" type="email" placeholder="seu@email.com" icon="envelope"
                        class="ring-2 ring-zinc-200/50 dark:ring-zinc-700/50 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm hover:ring-blue-300/50 focus:ring-blue-400/75 transition-all"
                        autocomplete="email" />
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Senha</label>
                    <flux:input wire:model.live="password" type="password" placeholder="••••••••" icon="lock-closed"
                        class="ring-2 ring-zinc-200/50 dark:ring-zinc-700/50 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm hover:ring-blue-300/50 focus:ring-blue-400/75 transition-all"
                        autocomplete="current-password" />
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <flux:checkbox wire:model="remember" class="text-sm">
                        <span class="text-zinc-600 dark:text-zinc-400">Lembrar-me</span>
                    </flux:checkbox>
                    <a href="#"
                        class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Esqueceu
                        a senha?</a>
                </div>
            </div>

            <!-- Botão glassmorphism -->
            <flux:button wire:click="login" block color="primary" icon="arrow-right"
                class="w-full mt-6 font-semibold shadow-lg hover:shadow-xl">
                Entrar na conta
            </flux:button>

            <!-- Footer -->
            <div class="pt-6 border-t border-zinc-200/50 dark:border-zinc-700/50 text-center">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    Não tem conta?
                    <a href="#"
                        class="font-semibold text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">Crie
                        grátis</a>
                </p>
            </div>
        </div>
    </div>
</div>