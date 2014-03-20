Ethereum CLL Preprocessor
------

**Features**

- Allows you to enter c-like `//` commented lines

 ```cpp
 // This is a comment.
 val = tx.data[0]
```
- Strips empty lines.

- Allows you to use c++ preprocessor-like defines

 ```cpp
 #define STATIC_ADDRESS 1001
 contract.storage[STATIC_ADDRESS] = 257
```

- Compiles your code using a branch of the Ethereum CLL Compiler: https://github.com/ethereum/compiler

- Allows you to compile just a snippet of code by deliminating the rest of the code with 3-or-more `=` signs (handy for debugging, or making reference code / notes)

 ```cpp
 // This gets processed.
 foo = 1 + 2
 contract.storage[100] = foo
 ====
 // This won't get processed.
 bar = 3 + 4
 contract.storage[101] = bar
```

----
**Examples**

Right now, it just outputs the compiled code to stdout, and you copy and paste it. All you have to do is call the bash script using your `.cll` file location as the first parameter.

    ./compile.sh yourfile.cll

Try it with the included cll file.

    ./compile.sh example.cll

----
**Setup**

You just have to init the submodules, and update, which will clone the other projects.

    [user@host dir]$ git submodule init
    [user@host dir]$ git submodule update

---
**To Do**

- Add `/* block style */` comments
- Add in-line comments (not yet supported) e.g. `if condition: // comment here`

---

This contains a submodule! http://git-scm.com/book/en/Git-Tools-Submodules

It's the cll compiler @ https://github.com/ethereum/compiler
