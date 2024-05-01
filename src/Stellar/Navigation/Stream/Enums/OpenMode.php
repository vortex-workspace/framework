<?php

namespace Stellar\Navigation\Stream\Enums;

/** https://www.php.net/manual/en/function.fopen.php */
enum OpenMode: string
{
    /** (r) => Open for reading only; place the file pointer at the beginning of the file.  */
    case R_MODE = 'r';
    /** (r+) => Open for reading and writing; place the file pointer at the beginning of the file.   */
    case R_PLUS_MODE = 'r+';
    case RB_MODE = 'rb';
    /**
     * (w) => Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero
     * length. If the file does not exist, attempt to create it.
     */
    case W_MODE = 'w';
    /** (w+) => Open for reading and writing; otherwise it has the same behavior as 'w'.  */
    case W_PLUS_MODE = 'w+';
    case WB_MODE = 'wb';
    /**
     * (a) => Open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt
     * to create it. In this mode, fseek() has no effect, writes are always appended.
     */
    case A_MODE = 'a';
    /**
     * (a+) => Open for reading and writing; place the file pointer at the end of the file. If the file does not exist,
     * attempt to create it. In this mode, fseek() only affects the reading position, writes are always appended.
     */
    case A_PLUS_MODE = 'a+';
    /**
     * (x) => Create and open for writing only; place the file pointer at the beginning of the file. If the file already
     * exists, the fopen() call will fail by returning false and generating an error of level E_WARNING. If the file
     * does not exist, attempt to create it. This is equivalent to specifying O_EXCL|O_CREAT flags for the underlying
     * open(2) system call.
     */
    case X_MODE = 'x';
    /** (x+) => Create and open for reading and writing; otherwise it has the same behavior as 'x'. */
    case X_PLUS_MODE = 'x+';
    /**
     * (c) => Open the file for writing only. If the file does not exist, it is created. If it exists, it is neither
     * truncated (as opposed to 'w'), nor the call to this function fails (as is the case with 'x'). The file pointer
     * is positioned on the beginning of the file. This may be useful if it's desired to get an advisory lock
     * (see flock()) before attempting to modify the file, as using 'w' could truncate the file before the lock was
     * obtained (if truncation is desired, ftruncate() can be used after the lock is requested).
     */
    case C_MODE = 'c';
    /** (c+) => Open the file for reading and writing; otherwise it has the same behavior as 'c'. */
    case C_PLUS_MODE = 'c+';
    /**
     * (e) => Set close-on-exec flag on the opened file descriptor. Only available in PHP compiled on POSIX.1-2008
     * conform systems.
     */
    case E_MODE = 'e';
}