syntax on
set tabstop=4
set expandtab
set shiftwidth=4
set softtabstop=4
set ignorecase
set nocompatible
set cursorcolumn " highlight the current column
set cursorline " highlight current line
set incsearch " BUT do highlight as you type you, search phrase
set laststatus=2 " always show the status line
set lazyredraw " do not redraw while running macros
set linespace=0 " don't insert any extra pixel lines & betweens rows
set list " we do what to show tabs, to ensure we get them & out of my files
set listchars=tab:>-,trail:- " show tabs and trailing-
set matchtime=5 " how many tenths of a second to blink & matching brackets for
set nohlsearch " do not highlight searched for phrases
set nostartofline " leave my cursor where it was
set novisualbell " don't blink
set number " turn on line numbers
set numberwidth=5 " We are good up to 99999 lines
set report=0 " tell us when anything is changed via :...
set ruler " Always show current positions along the bottom
set scrolloff=10 " Keep 10 lines (top/bottom) for scope
set shortmess=aOstT " shortens messages to avoid & 'press a key' prompt
set showcmd " show the command being typed
set showmatch " show matching brackets
set sidescrolloff=10 " Keep 5 lines at the size
set statusline=%F%m%r%h%w[%L][%{&ff}]%y[%p%%][%04l,%04v]
              " | | | | |  |   |      |  |     |    |
              " | | | | |  |   |      |  |     |    + current-
              " | | | | |  |   |      |  |     |       column
              " | | | | |  |   |      |  |     +-- current line
              " | | | | |  |   |      |  +-- current % into file
              " | | | | |  |   |      +-- current syntax in-
              " | | | | |  |   |          square brackets
              " | | | | |  |   +-- current fileformat
              " | | | | |  +-- number of lines
              " | | | | +-- preview flag in square brackets
              " | | | +-- help flag in square brackets
              " | | +-- readonly flag in square brackets
              " | +-- rodified flag in square brackets
              " +-- full path to file in the buffer
